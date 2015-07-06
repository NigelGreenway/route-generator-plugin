/**
 * A Route Generator plugin for javascript to
 * generate routes dynamically
 *
 * @author Nigel Greenway <nigel_greenway@me.com>
 */
(function (window) {

    /**
     * Get a built up version of the location with the protocol,
     * host and port
     *
     * @returns {string}
     */
    function getLocation() {
        var
            protocol = window.location.protocol,
            host     = window.location.hostname,
            port     = window.location.port.length > 0 ? ':' + window.location.port : '';

        return protocol + '//' + host + port;
    };

    /**
     * Routing constructor
     *
     * @param routes
     *
     * @constructor
     */
    var Routing = function(routes) {
        this.routes   = JSON.parse(routes);
        this.location = getLocation();
    };

    /**
     * All methods for the Routing plugin
     *
     * @type {{generate: Function, buildRoute: Function, findRoute: Function}}
     */
    Routing.prototype = {

        /**
         * Generate a route from its alias and its parameters and the optional
         * absolute parameter
         *
         * @param {string}       alias
         * @param {object}       parameters
         * @param {boolean|null} absolute
         *
         * @returns {string}
         */
        generate: function (alias, parameters, absolute) {

            var absolute = absolute || false;

            if (absolute === true) {
                return this.location + this.buildRoute(alias, this.findRoute(alias), parameters).join('/');
            }
            return this.buildRoute(alias, this.findRoute(alias), parameters).join('/');
        },

        /**
         * Build the route
         *
         * @param {string} alias
         * @param {array}  routeData
         * @param {object} parameters
         *
         * @returns {Array}
         */
        buildRoute: function (alias, routeData, parameters) {
            var elements = routeData['pattern'].split('/').map(function (element) {
                if (element.match(/\{\w+}/g)) {
                    if (typeof parameters[element.match(/\w+/g)] == 'undefined') {
                        throw new Error('The route [' + alias + '] requires [' + element.match(/\w+/g)[0] + '] as a parameter.');
                    }
                    return parameters[element.match(/\w+/g)];
                }
                return element;
            });

            return elements;
        },

        /**
         * Find if the route exists in the routes array
         *
         * @param string alias
         *
         * @returns {*}
         */
        findRoute: function (alias) {
            if (typeof this.routes[alias] == 'undefined') {
                throw new Error('The route [' + alias + '] does not exist or is not exposed');
            }
            return this.routes[alias];
        }
    };

    window.Routing = Routing;

}) (window);
