(function(t, e) {
    "object" == typeof exports && "object" == typeof module ? module.exports = e() : "function" == typeof define && define.amd ? define([], e) : "object" == typeof exports ? exports.VToaster = e() : t.VToaster = e()
})(this, function() {
    return function(t) {
        function e(o) {
            if (n[o]) return n[o].exports;
            var r = n[o] = {
                i: o,
                l: !1,
                exports: {}
            };
            return t[o].call(r.exports, r, r.exports, e), r.l = !0, r.exports
        }
        var n = {};
        return e.m = t, e.c = n, e.i = function(t) {
            return t
        }, e.d = function(t, n, o) {
            e.o(t, n) || Object.defineProperty(t, n, {
                configurable: !1,
                enumerable: !0,
                get: o
            })
        }, e.n = function(t) {
            var n = t && t.__esModule ? function() {
                return t.default
            } : function() {
                return t
            };
            return e.d(n, "a", n), n
        }, e.o = function(t, e) {
            return Object.prototype.hasOwnProperty.call(t, e)
        }, e.p = ".", e(e.s = 6)
    }([function(t, e, n) {
        var o = n(3);
        o.install = function(t, e) {
            t.prototype.$toaster = new(t.extend(o))({
                propsData: e
            }), t.toaster = t.prototype.$toaster
        }, t.exports = o
    }, function(t, e, n) {
        "use strict";
        Object.defineProperty(e, "__esModule", {
            value: !0
        }), e.default = {
            props: {
                timeout: {
                    type: Number,
                    default: 1e4
                }
            },
            methods: {
                success: function(t) {
                    var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {};
                    this.add(t, {
                        theme: "v-toast-success",
                        timeout: e.timeout
                    })
                },
                info: function(t) {
                    var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {};
                    this.add(t, {
                        theme: "v-toast-info",
                        timeout: e.timeout
                    })
                },
                warning: function(t) {
                    var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {};
                    this.add(t, {
                        theme: "v-toast-warning",
                        timeout: e.timeout
                    })
                },
                error: function(t) {
                    var e = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {};
                    this.add(t, {
                        theme: "v-toast-error",
                        timeout: e.timeout
                    })
                },
                add: function(t, e) {
                    var n = this,
                        o = e.theme,
                        r = e.timeout;
                    this.$parent || (this.$mount(), document.body.appendChild(this.$el));
                    var i = {
                        message: t,
                        theme: o,
                        key: Date.now() + "-" + Math.random()
                    };
                    this.items.push(i), setTimeout(function() {
                        return n.remove(i)
                    }, r || this.timeout)
                },
                remove: function(t) {
                    var e = this.items.indexOf(t);
                    e >= 0 && this.items.splice(e, 1)
                }
            },
            data: function() {
                return {
                    items: []
                }
            }
        }
    }, function(t, e) {}, function(t, e, n) {
        n(2);
        var o = n(4)(n(1), n(5), null, null);
        t.exports = o.exports
    }, function(t, e) {
        t.exports = function(t, e, n, o) {
            var r, i = t = t || {},
                s = typeof t.default;
            "object" !== s && "function" !== s || (r = t, i = t.default);
            var u = "function" == typeof i ? i.options : i;
            if (e && (u.render = e.render, u.staticRenderFns = e.staticRenderFns), n && (u._scopeId = n), o) {
                var a = u.computed || (u.computed = {});
                Object.keys(o).forEach(function(t) {
                    var e = o[t];
                    a[t] = function() {
                        return e
                    }
                })
            }
            return {
                esModule: r,
                exports: i,
                options: u
            }
        }
    }, function(t, e) {
        t.exports = {
            render: function() {
                var t = this,
                    e = t.$createElement,
                    n = t._self._c || e;
                return n("div", {
                    staticClass: "v-toaster"
                }, [n("transition-group", {
                    attrs: {
                        name: "v-toast"
                    }
                }, t._l(t.items, function(e) {
                    return n("div", {
                        key: e.key,
                        staticClass: "v-toast",
                        class: (o = {}, o[e.theme] = e.theme, o)
                    }, [n("a", {
                        staticClass: "v-toast-btn-clear",
                        on: {
                            click: function(n) {
                                t.remove(e)
                            }
                        }
                    }), t._v(t._s(e.message))]);
                    var o
                }))], 1)
            },
            staticRenderFns: []
        }
    }, function(t, e, n) {
        t.exports = n(0)
    }])
});