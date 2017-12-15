var Toolset = Toolset || {};

Toolset.Gui = Toolset.Gui || {};

/**
 * Abstract controller for Toolset admin pages.
 *
 * It offers generic support for loading data passed from PHP in a safe way, initializing Knockout, helper function
 * for accessing underscore templates provided by PHP, loading dependencies and, finally, loading the main page viewmodel.
 *
 * See Toolset_Gui_Base for thorough documentation.
 *
 * @constructor
 * @since 2.2
 */
Toolset.Gui.AbstractPage = function() {

    var self = this;

    /**
     * Log all arguments to console if debugging is turned on.
     *
     * @since 2.0
     */
    self.debug = function () {
        if (self.isDebug) {
            console.log.apply(console, arguments);
        }
    };


    self.isDebug = false;


    /**
     * Log an arbitrary number of arguments.
     *
     * @since 2.2
     */
    self.log = function() {
        console.log.apply(console, arguments);
    };


    /**
     * Read model data from PHP passed in a standard way through Toolset_Gui_Base and Twig.
     *
     * The result will be stored in self.modelData.
     *
     * @param {string} [selector] CSS selector to target the element with the encoded model data. Defaults to
     *     the Toolset GUI Base default value, so better leave it alone. It is taken into account only first time
     *     this function is called.
     *
     * @returns {*} The loaded model data.
     *
     * @since 2.2
     */
    self.getModelData = function(selector) {

        if(!_.has(this, 'modelData')) {
            if(typeof(selector) == 'undefined') {
                selector = '#toolset_model_data';
            }

            self.modelData = jQuery.parseJSON(WPV_Toolset.Utils.editor_decode64(jQuery(selector).html()));
        }

        return self.modelData;
    };


    /**
     * Safely retrieve a string from modelData.
     *
     * It expects the string to be placed in modelData.strings.
     *
     * @param {string|[string]} stringPath Name of the string or its path
     *     (['path', 'to', 'string'] for modelData.strings.path.to.string).
     * @returns {string} The requested string or an empty string.
     * @since m2m
     */
    self.getString = function(stringPath) {

        var modelData = self.getModelData();

        if (!_.has(modelData, 'strings')) {
            return '';
        }

        var getString = function (stringPath, source) {

            if (_.isArray(stringPath)) {

                if(stringPath.length === 1) {
                    return getString(_.first(stringPath), source);
                }

                var key = _.head(stringPath);
                var subpath = _.tail(stringPath);

                if (!_.has(source, key)) {
                    return '';
                }

                return getString(subpath, source[key]);
            } else if (_.isString(stringPath) && _.has(source, stringPath)) {
                return source[stringPath];
            }

            return '';

        };

        return getString(stringPath, modelData['strings']);
    };


    /**
     * Initialize the getter function for templates.
     *
     * Creates a self.templates helper with functions for retrieving and rendering a template. If used correctly,
     * only self.templates.renderUnderscore will be needed.
     *
     * Can be extended to allow for different types of templates in the future.
     *
     * The set of available templates is determined by the "templates" property of model data.
     *
     * @since 2.2
     */
    self.initTemplates = function() {

        var modelData = self.getModelData();

        if( _.has(modelData, 'templates') && _.isObject(_.property('templates')(modelData))) {

            self.templates = new function() {

                var templates = this;

                templates.raw = _.property('templates')(modelData);

                /**
                 * @param {string} templateName
                 * @returns {string} Raw template content.
                 */
                templates.getRawTemplate = function(templateName) {
                    if(_.has(templates.raw, templateName)) {
                        return templates.raw[templateName];
                    } else {
                        self.log('Template "' + templateName + '" not found.');
                        return '';
                    }
                };

                templates.compiledUnderscoreTemplates = {};

                /**
                 * @param {string} templateName
                 * @returns {function} Compiled underscore template
                 */
                templates.getUnderscoreTemplate = function(templateName) {
                    if(!_.has(templates.compiledUnderscoreTemplates, templateName)) {
                        templates.compiledUnderscoreTemplates[templateName] = _.template(templates.getRawTemplate(templateName));
                    }
                    return templates.compiledUnderscoreTemplates[templateName];
                };


                /**
                 * Compile an underscore template (with using cache) and render it.
                 *
                 * @param {string} templateName
                 * @param {object} context Underscore context for rendering the template.
                 * @returns {string} Rendered markup.
                 */
                templates.renderUnderscore = function(templateName, context) {
                    var compiled = templates.getUnderscoreTemplate(templateName);
                    return compiled(context);
                };

            };
        }
    };

    /**
     * Initialize custom Knockout bindings and other modifications.
     *
     * @since 2.2
     */
    self.initKnockout = function() {

        var $ = jQuery;

        // Taken from http://knockoutjs.com/examples/animatedTransitions.html
        // Here's a custom Knockout binding that makes elements shown/hidden via jQuery's fadeIn()/fadeOut() methods
        ko.bindingHandlers.fadeVisible = {
            init: function(element, valueAccessor) {
                // Initially set the element to be instantly visible/hidden depending on the value
                var value = valueAccessor();
                $(element).toggle(ko.unwrap(value)); // Use "unwrapObservable" so we can handle values that may or may not be observable
            },
            update: function(element, valueAccessor) {
                // Whenever the value subsequently changes, slowly fade the element in or out
                var value = valueAccessor();
                ko.unwrap(value) ? $(element).fadeIn() : $(element).fadeOut();
            }
        };


        var applyDisplayMode = function(displayMode, element, immediately) {
            switch(displayMode) {
                case 'show':
                    element.css('visibility', 'visible');
                    if(immediately) {
                        element.show();
                    } else {
                        element.slideDown().css('display', 'none').fadeIn();
                    }
                    break;
                case 'hide':
                    element.css('visibility', 'hidden');
                    if(immediately) {
                        element.show();
                    } else {
                        element.slideDown();
                    }
                    break;
                case 'remove':
                    if(immediately) {
                        element.hide();
                    } else {
                        element.slideUp().fadeOut();
                    }
                    element.css('visibility', 'hidden');
                    break;
            }
        };


        /**
         * Binding for displaying an element in three modes:
         *
         * - 'show' will simply display the element
         * - 'hide' will hide it, but leave the free space for another message to be displayed soon
         * - 'remove' will hide it completely
         *
         * Show/remove values use animations.
         *
         * @since 2.2
         */
        ko.bindingHandlers.threeModeVisibility = {
            init: function(element, valueAccessor) {
                var displayMode = ko.unwrap(valueAccessor());
                applyDisplayMode(displayMode, $(element), true);
            },
            update: function(element, valueAccessor) {
                var displayMode = ko.unwrap(valueAccessor());
                applyDisplayMode(displayMode, $(element), false);
            }
        };


        var disablePrimary = function(element, valueAccessor) {
            var isDisabled = ko.unwrap(valueAccessor());
            if(isDisabled) {
                $(element).prop('disabled', true).removeClass('button-primary');
            } else {
                $(element).prop('disabled', false).addClass('button-primary');
            }
        };

        /**
         * Disable primary button and update its class.
         *
         * @since 2.2
         */
        ko.bindingHandlers.disablePrimary = {
            init: disablePrimary,
            update: disablePrimary
        };


        var redButton = function(element, valueAccessor) {
            var isRed = ko.unwrap(valueAccessor());
            if(isRed) {
                jQuery(element).addClass('toolset-red-button');
            } else {
                jQuery(element).removeClass('toolset-red-button');
            }
        };


        /**
         * Add or remove a class that makes a button red.
         *
         * @since 2.0
         */
        ko.bindingHandlers.redButton = {
            init: redButton,
            update: redButton
        };


        // Update textarea's value and scroll it to the bottom.
        var valueScroll = function(element, valueAccessor) {
            var value = ko.unwrap(valueAccessor());
            var textarea = $(element);

            textarea.val(value);
            textarea.scrollTop(textarea[0].scrollHeight);
        };

        ko.bindingHandlers.valueScroll = {
            init: valueScroll,
            update: valueScroll
        };


        /**
         * Set the readonly attribute value.
         *
         * @type {{update: ko.bindingHandlers.readOnly.update}}
         * @since m2m
         */
        ko.bindingHandlers.readOnly = {
            update: function(element, valueAccessor) {
                var value = ko.utils.unwrapObservable(valueAccessor());
                if (value) {
                    element.setAttribute("readonly", true);
                }  else {
                    element.removeAttribute("readonly");
                }
            }
        }

				/**
         * New computed type that allows to force the reading on the observable
         *
         * Check this {@link https://stackoverflow.com/questions/13769481/force-a-computed-property-function-to-run/29960082#29960082|Stackoveflow} example
         * @since m2m
         */
        ko.notifyingWritableComputed = function(options, context) {
            var _notifyTrigger = ko.observable(0);
            var originalRead = options.read;
            var originalWrite = options.write;

            // intercept 'read' function provided in options
            options.read = function() {
                // read the dummy observable, which if updated will
                // force subscribers to receive the new value
                _notifyTrigger();
                return originalRead();
            };

            // intercept 'write' function
            options.write = function(v) {
                // run logic provided by user
                originalWrite(v);

                // force reevaluation of the notifyingWritableComputed
                // after we have called the original write logic
                _notifyTrigger(_notifyTrigger() + 1);
            };

            // just create computed as normal with all the standard parameters
            return ko.computed(options, context);
        }
    };


    /**
     * Create a Toolset dialog.
     *
     * For details, see https://git.onthegosystems.com/toolset/toolset-common/wikis/best-practices/dialogs.
     *
     * @param {string} dialogId Id of the HTML element holding the dialog template.
     * @param {string} title Dialog title to be displayed
     * @param {*} templateContext Context for the dialog (underscore) template.
     * @param buttons Button definitions according to jQuery UI Dialogs.
     * @param [options] Further options that will be passed directly.
     * @returns {{DDLayout.DialogView}} A dialog object.
     * @since 2.1
     */
    self.createDialog = function(dialogId, title, templateContext, buttons, options) {

        var dialogDuplicate = DDLayout.DialogView.extend({});

        var dialog = new dialogDuplicate(_.defaults(options || {}, {
            title: title,
            selector: '#' + dialogId,
            template_object: templateContext,
            buttons: buttons,
            width: 600
        }));

        return dialog;
    };


    /**
     * This will be called before the first step of controller initialization.
     *
     * @since 2.2
     */
    self.beforeInit = function() {};


    /**
     * This will be called as the last step of the controller initialization.
     *
     * @since 2.2
     */
    self.afterInit = function() {};


    /**
     * Load dependencies (e.g. by head.js) and continue by calling the nextStep callback when ready.
     *
     * To be overridden.
     *
     * @param {function} nextStep
     * @since 2.2
     */
    self.loadDependencies = function(nextStep) { nextStep(); };

    /**
     * Create and initialize the main ViewModel for the page.
     *
     * To be overridden.
     *
     * @return {*}
     * @since 2.2
     */
    self.getMainViewModel = function() {};


    /**
     * Get the jQuery element that wraps the whole page.
     *
     * @returns {*}
     * @since 2.2
     */
    self.getPageContent = function() {
        return jQuery('#toolset-page-content');
    };


    /**
     * Initialize the main viewmodel.
     *
     * That means creating it and then hiding the spinner that was displayed by default, and displaying the
     * wrapper for the main page content that was hidden by default.
     *
     * @since 2.2
     */
    self.initMainViewModel = function() {

        self.viewModel = self.getMainViewModel();

        var pageContent = self.getPageContent();

        // Show the listing after it's been fully rendered by knockout.
        pageContent.find('.toolset-page-spinner').hide();
        pageContent.find('.toolset-actual-content-wrapper').show();

    };


    /**
     * The whole initialization sequence.
     *
     * @since 2.2
     */
    self.init = function() {
        self.beforeInit();

        self.initTemplates();
        self.initKnockout();

        self.loadDependencies(function() {
            self.initMainViewModel();

            self.afterInit();
        });

    };

};
