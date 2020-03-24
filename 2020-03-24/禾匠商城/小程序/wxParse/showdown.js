var _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
    return typeof e;
} : function(e) {
    return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e;
};

function getDefaultOpts(e) {
    var r = {
        omitExtraWLInCodeBlocks: {
            defaultValue: !1,
            describe: "Omit the default extra whiteline added to code blocks",
            type: "boolean"
        },
        noHeaderId: {
            defaultValue: !1,
            describe: "Turn on/off generated header id",
            type: "boolean"
        },
        prefixHeaderId: {
            defaultValue: !1,
            describe: "Specify a prefix to generated header ids",
            type: "string"
        },
        headerLevelStart: {
            defaultValue: !1,
            describe: "The header blocks level start",
            type: "integer"
        },
        parseImgDimensions: {
            defaultValue: !1,
            describe: "Turn on/off image dimension parsing",
            type: "boolean"
        },
        simplifiedAutoLink: {
            defaultValue: !1,
            describe: "Turn on/off GFM autolink style",
            type: "boolean"
        },
        literalMidWordUnderscores: {
            defaultValue: !1,
            describe: "Parse midword underscores as literal underscores",
            type: "boolean"
        },
        strikethrough: {
            defaultValue: !1,
            describe: "Turn on/off strikethrough support",
            type: "boolean"
        },
        tables: {
            defaultValue: !1,
            describe: "Turn on/off tables support",
            type: "boolean"
        },
        tablesHeaderId: {
            defaultValue: !1,
            describe: "Add an id to table headers",
            type: "boolean"
        },
        ghCodeBlocks: {
            defaultValue: !0,
            describe: "Turn on/off GFM fenced code blocks support",
            type: "boolean"
        },
        tasklists: {
            defaultValue: !1,
            describe: "Turn on/off GFM tasklist support",
            type: "boolean"
        },
        smoothLivePreview: {
            defaultValue: !1,
            describe: "Prevents weird effects in live previews due to incomplete input",
            type: "boolean"
        },
        smartIndentationFix: {
            defaultValue: !1,
            description: "Tries to smartly fix identation in es6 strings",
            type: "boolean"
        }
    };
    if (!1 === e) return JSON.parse(JSON.stringify(r));
    var n = {};
    for (var t in r) r.hasOwnProperty(t) && (n[t] = r[t].defaultValue);
    return n;
}

var showdown = {}, parsers = {}, extensions = {}, globalOptions = getDefaultOpts(!0), flavor = {
    github: {
        omitExtraWLInCodeBlocks: !0,
        prefixHeaderId: "user-content-",
        simplifiedAutoLink: !0,
        literalMidWordUnderscores: !0,
        strikethrough: !0,
        tables: !0,
        tablesHeaderId: !0,
        ghCodeBlocks: !0,
        tasklists: !0
    },
    vanilla: getDefaultOpts(!0)
};

function validate(e, r) {
    var n = r ? "Error in " + r + " extension->" : "Error in unnamed extension", t = {
        valid: !0,
        error: ""
    };
    showdown.helper.isArray(e) || (e = [ e ]);
    for (var o = 0; o < e.length; ++o) {
        var s = n + " sub-extension " + o + ": ", a = e[o];
        if ("object" !== (void 0 === a ? "undefined" : _typeof(a))) return t.valid = !1, 
        t.error = s + "must be an object, but " + (void 0 === a ? "undefined" : _typeof(a)) + " given", 
        t;
        if (!showdown.helper.isString(a.type)) return t.valid = !1, t.error = s + 'property "type" must be a string, but ' + _typeof(a.type) + " given", 
        t;
        var i = a.type = a.type.toLowerCase();
        if ("language" === i && (i = a.type = "lang"), "html" === i && (i = a.type = "output"), 
        "lang" !== i && "output" !== i && "listener" !== i) return t.valid = !1, t.error = s + "type " + i + ' is not recognized. Valid values: "lang/language", "output/html" or "listener"', 
        t;
        if ("listener" === i) {
            if (showdown.helper.isUndefined(a.listeners)) return t.valid = !1, t.error = s + '. Extensions of type "listener" must have a property called "listeners"', 
            t;
        } else if (showdown.helper.isUndefined(a.filter) && showdown.helper.isUndefined(a.regex)) return t.valid = !1, 
        t.error = s + i + ' extensions must define either a "regex" property or a "filter" method', 
        t;
        if (a.listeners) {
            if ("object" !== _typeof(a.listeners)) return t.valid = !1, t.error = s + '"listeners" property must be an object but ' + _typeof(a.listeners) + " given", 
            t;
            for (var l in a.listeners) if (a.listeners.hasOwnProperty(l) && "function" != typeof a.listeners[l]) return t.valid = !1, 
            t.error = s + '"listeners" property must be an hash of [event name]: [callback]. listeners.' + l + " must be a function but " + _typeof(a.listeners[l]) + " given", 
            t;
        }
        if (a.filter) {
            if ("function" != typeof a.filter) return t.valid = !1, t.error = s + '"filter" must be a function, but ' + _typeof(a.filter) + " given", 
            t;
        } else if (a.regex) {
            if (showdown.helper.isString(a.regex) && (a.regex = new RegExp(a.regex, "g")), !a.regex instanceof RegExp) return t.valid = !1, 
            t.error = s + '"regex" property must either be a string or a RegExp object, but ' + _typeof(a.regex) + " given", 
            t;
            if (showdown.helper.isUndefined(a.replace)) return t.valid = !1, t.error = s + '"regex" extensions must implement a replace string or function', 
            t;
        }
    }
    return t;
}

function escapeCharactersCallback(e, r) {
    return "~E" + r.charCodeAt(0) + "E";
}

showdown.helper = {}, showdown.extensions = {}, showdown.setOption = function(e, r) {
    return globalOptions[e] = r, this;
}, showdown.getOption = function(e) {
    return globalOptions[e];
}, showdown.getOptions = function() {
    return globalOptions;
}, showdown.resetOptions = function() {
    globalOptions = getDefaultOpts(!0);
}, showdown.setFlavor = function(e) {
    if (flavor.hasOwnProperty(e)) {
        var r = flavor[e];
        for (var n in r) r.hasOwnProperty(n) && (globalOptions[n] = r[n]);
    }
}, showdown.getDefaultOptions = function(e) {
    return getDefaultOpts(e);
}, showdown.subParser = function(e, r) {
    if (showdown.helper.isString(e)) {
        if (void 0 === r) {
            if (parsers.hasOwnProperty(e)) return parsers[e];
            throw Error("SubParser named " + e + " not registered!");
        }
        parsers[e] = r;
    }
}, showdown.extension = function(e, r) {
    if (!showdown.helper.isString(e)) throw Error("Extension 'name' must be a string");
    if (e = showdown.helper.stdExtName(e), showdown.helper.isUndefined(r)) {
        if (!extensions.hasOwnProperty(e)) throw Error("Extension named " + e + " is not registered!");
        return extensions[e];
    }
    "function" == typeof r && (r = r()), showdown.helper.isArray(r) || (r = [ r ]);
    var n = validate(r, e);
    if (!n.valid) throw Error(n.error);
    extensions[e] = r;
}, showdown.getAllExtensions = function() {
    return extensions;
}, showdown.removeExtension = function(e) {
    delete extensions[e];
}, showdown.resetExtensions = function() {
    extensions = {};
}, showdown.validateExtension = function(e) {
    var r = validate(e, null);
    return !!r.valid || (console.warn(r.error), !1);
}, showdown.hasOwnProperty("helper") || (showdown.helper = {}), showdown.helper.isString = function(e) {
    return "string" == typeof e || e instanceof String;
}, showdown.helper.isFunction = function(e) {
    return e && "[object Function]" === {}.toString.call(e);
}, showdown.helper.forEach = function(e, r) {
    if ("function" == typeof e.forEach) e.forEach(r); else for (var n = 0; n < e.length; n++) r(e[n], n, e);
}, showdown.helper.isArray = function(e) {
    return e.constructor === Array;
}, showdown.helper.isUndefined = function(e) {
    return void 0 === e;
}, showdown.helper.stdExtName = function(e) {
    return e.replace(/[_-]||\s/g, "").toLowerCase();
}, showdown.helper.escapeCharactersCallback = escapeCharactersCallback, showdown.helper.escapeCharacters = function(e, r, n) {
    var t = "([" + r.replace(/([\[\]\\])/g, "\\$1") + "])";
    n && (t = "\\\\" + t);
    var o = new RegExp(t, "g");
    return e = e.replace(o, escapeCharactersCallback);
};

var rgxFindMatchPos = function(e, r, n, t) {
    var o, s, a, i, l, c = t || "", h = -1 < c.indexOf("g"), d = new RegExp(r + "|" + n, "g" + c.replace(/g/g, "")), u = new RegExp(r, c.replace(/g/g, "")), p = [];
    do {
        for (o = 0; a = d.exec(e); ) if (u.test(a[0])) o++ || (i = (s = d.lastIndex) - a[0].length); else if (o && !--o) {
            l = a.index + a[0].length;
            var w = {
                left: {
                    start: i,
                    end: s
                },
                match: {
                    start: s,
                    end: a.index
                },
                right: {
                    start: a.index,
                    end: l
                },
                wholeMatch: {
                    start: i,
                    end: l
                }
            };
            if (p.push(w), !h) return p;
        }
    } while (o && (d.lastIndex = s));
    return p;
};

showdown.helper.matchRecursiveRegExp = function(e, r, n, t) {
    for (var o = rgxFindMatchPos(e, r, n, t), s = [], a = 0; a < o.length; ++a) s.push([ e.slice(o[a].wholeMatch.start, o[a].wholeMatch.end), e.slice(o[a].match.start, o[a].match.end), e.slice(o[a].left.start, o[a].left.end), e.slice(o[a].right.start, o[a].right.end) ]);
    return s;
}, showdown.helper.replaceRecursiveRegExp = function(e, r, n, t, o) {
    if (!showdown.helper.isFunction(r)) {
        var s = r;
        r = function() {
            return s;
        };
    }
    var a = rgxFindMatchPos(e, n, t, o), i = e, l = a.length;
    if (0 < l) {
        var c = [];
        0 !== a[0].wholeMatch.start && c.push(e.slice(0, a[0].wholeMatch.start));
        for (var h = 0; h < l; ++h) c.push(r(e.slice(a[h].wholeMatch.start, a[h].wholeMatch.end), e.slice(a[h].match.start, a[h].match.end), e.slice(a[h].left.start, a[h].left.end), e.slice(a[h].right.start, a[h].right.end))), 
        h < l - 1 && c.push(e.slice(a[h].wholeMatch.end, a[h + 1].wholeMatch.start));
        a[l - 1].wholeMatch.end < e.length && c.push(e.slice(a[l - 1].wholeMatch.end)), 
        i = c.join("");
    }
    return i;
}, showdown.helper.isUndefined(console) && (console = {
    warn: function(e) {
        alert(e);
    },
    log: function(e) {
        alert(e);
    },
    error: function(e) {
        throw e;
    }
}), showdown.Converter = function(n) {
    var s = {}, a = [], i = [], l = {};
    function t(e, r) {
        if (r = r || null, showdown.helper.isString(e)) {
            if (r = e = showdown.helper.stdExtName(e), showdown.extensions[e]) return console.warn("DEPRECATION WARNING: " + e + " is an old extension that uses a deprecated loading method.Please inform the developer that the extension should be updated!"), 
            void function(e, r) {
                "function" == typeof e && (e = e(new showdown.Converter()));
                showdown.helper.isArray(e) || (e = [ e ]);
                var n = validate(e, r);
                if (!n.valid) throw Error(n.error);
                for (var t = 0; t < e.length; ++t) switch (e[t].type) {
                  case "lang":
                    a.push(e[t]);
                    break;

                  case "output":
                    i.push(e[t]);
                    break;

                  default:
                    throw Error("Extension loader error: Type unrecognized!!!");
                }
            }(showdown.extensions[e], e);
            if (showdown.helper.isUndefined(extensions[e])) throw Error('Extension "' + e + '" could not be loaded. It was either not found or is not a valid extension.');
            e = extensions[e];
        }
        "function" == typeof e && (e = e()), showdown.helper.isArray(e) || (e = [ e ]);
        var n = validate(e, r);
        if (!n.valid) throw Error(n.error);
        for (var t = 0; t < e.length; ++t) {
            switch (e[t].type) {
              case "lang":
                a.push(e[t]);
                break;

              case "output":
                i.push(e[t]);
            }
            if (e[t].hasOwnProperty(l)) for (var o in e[t].listeners) e[t].listeners.hasOwnProperty(o) && c(o, e[t].listeners[o]);
        }
    }
    function c(e, r) {
        if (!showdown.helper.isString(e)) throw Error("Invalid argument in converter.listen() method: name must be a string, but " + (void 0 === e ? "undefined" : _typeof(e)) + " given");
        if ("function" != typeof r) throw Error("Invalid argument in converter.listen() method: callback must be a function, but " + (void 0 === r ? "undefined" : _typeof(r)) + " given");
        l.hasOwnProperty(e) || (l[e] = []), l[e].push(r);
    }
    !function() {
        for (var e in n = n || {}, globalOptions) globalOptions.hasOwnProperty(e) && (s[e] = globalOptions[e]);
        {
            if ("object" !== (void 0 === n ? "undefined" : _typeof(n))) throw Error("Converter expects the passed parameter to be an object, but " + (void 0 === n ? "undefined" : _typeof(n)) + " was passed instead.");
            for (var r in n) n.hasOwnProperty(r) && (s[r] = n[r]);
        }
        s.extensions && showdown.helper.forEach(s.extensions, t);
    }(), this._dispatch = function(e, r, n, t) {
        if (l.hasOwnProperty(e)) for (var o = 0; o < l[e].length; ++o) {
            var s = l[e][o](e, r, this, n, t);
            s && void 0 !== s && (r = s);
        }
        return r;
    }, this.listen = function(e, r) {
        return c(e, r), this;
    }, this.makeHtml = function(r) {
        if (!r) return r;
        var e, n, t, o = {
            gHtmlBlocks: [],
            gHtmlMdBlocks: [],
            gHtmlSpans: [],
            gUrls: {},
            gTitles: {},
            gDimensions: {},
            gListLevel: 0,
            hashLinkCounts: {},
            langExtensions: a,
            outputModifiers: i,
            converter: this,
            ghCodeBlocks: []
        };
        return r = (r = (r = (r = r.replace(/~/g, "~T")).replace(/\$/g, "~D")).replace(/\r\n/g, "\n")).replace(/\r/g, "\n"), 
        s.smartIndentationFix && (n = (e = r).match(/^\s*/)[0].length, t = new RegExp("^\\s{0," + n + "}", "gm"), 
        r = e.replace(t, "")), r = r, r = showdown.subParser("detab")(r, s, o), r = showdown.subParser("stripBlankLines")(r, s, o), 
        showdown.helper.forEach(a, function(e) {
            r = showdown.subParser("runExtension")(e, r, s, o);
        }), r = showdown.subParser("hashPreCodeTags")(r, s, o), r = showdown.subParser("githubCodeBlocks")(r, s, o), 
        r = showdown.subParser("hashHTMLBlocks")(r, s, o), r = showdown.subParser("hashHTMLSpans")(r, s, o), 
        r = showdown.subParser("stripLinkDefinitions")(r, s, o), r = showdown.subParser("blockGamut")(r, s, o), 
        r = showdown.subParser("unhashHTMLSpans")(r, s, o), r = (r = (r = showdown.subParser("unescapeSpecialChars")(r, s, o)).replace(/~D/g, "$$")).replace(/~T/g, "~"), 
        showdown.helper.forEach(i, function(e) {
            r = showdown.subParser("runExtension")(e, r, s, o);
        }), r;
    }, this.setOption = function(e, r) {
        s[e] = r;
    }, this.getOption = function(e) {
        return s[e];
    }, this.getOptions = function() {
        return s;
    }, this.addExtension = function(e, r) {
        t(e, r = r || null);
    }, this.useExtension = function(e) {
        t(e);
    }, this.setFlavor = function(e) {
        if (flavor.hasOwnProperty(e)) {
            var r = flavor[e];
            for (var n in r) r.hasOwnProperty(n) && (s[n] = r[n]);
        }
    }, this.removeExtension = function(e) {
        showdown.helper.isArray(e) || (e = [ e ]);
        for (var r = 0; r < e.length; ++r) {
            for (var n = e[r], t = 0; t < a.length; ++t) a[t] === n && a[t].splice(t, 1);
            for (;0 < i.length; ++t) i[0] === n && i[0].splice(t, 1);
        }
    }, this.getAllExtensions = function() {
        return {
            language: a,
            output: i
        };
    };
}, showdown.subParser("anchors", function(e, r, p) {
    var n = function(e, r, n, t, o, s, a, i) {
        showdown.helper.isUndefined(i) && (i = ""), e = r;
        var l = n, c = t.toLowerCase(), h = o, d = i;
        if (!h) if (c || (c = l.toLowerCase().replace(/ ?\n/g, " ")), h = "#" + c, showdown.helper.isUndefined(p.gUrls[c])) {
            if (!(-1 < e.search(/\(\s*\)$/m))) return e;
            h = "";
        } else h = p.gUrls[c], showdown.helper.isUndefined(p.gTitles[c]) || (d = p.gTitles[c]);
        var u = '<a href="' + (h = showdown.helper.escapeCharacters(h, "*_", !1)) + '"';
        return "" !== d && null !== d && (d = d.replace(/"/g, "&quot;"), u += ' title="' + (d = showdown.helper.escapeCharacters(d, "*_", !1)) + '"'), 
        u += ">" + l + "</a>";
    };
    return e = (e = (e = (e = p.converter._dispatch("anchors.before", e, r, p)).replace(/(\[((?:\[[^\]]*]|[^\[\]])*)][ ]?(?:\n[ ]*)?\[(.*?)])()()()()/g, n)).replace(/(\[((?:\[[^\]]*]|[^\[\]])*)]\([ \t]*()<?(.*?(?:\(.*?\).*?)?)>?[ \t]*((['"])(.*?)\6[ \t]*)?\))/g, n)).replace(/(\[([^\[\]]+)])()()()()()/g, n), 
    e = p.converter._dispatch("anchors.after", e, r, p);
}), showdown.subParser("autoLinks", function(e, r, n) {
    function t(e, r) {
        var n = r;
        return /^www\./i.test(r) && (r = r.replace(/^www\./i, "http://www.")), '<a href="' + r + '">' + n + "</a>";
    }
    function o(e, r) {
        var n = showdown.subParser("unescapeSpecialChars")(r);
        return showdown.subParser("encodeEmailAddress")(n);
    }
    return e = (e = (e = n.converter._dispatch("autoLinks.before", e, r, n)).replace(/<(((https?|ftp|dict):\/\/|www\.)[^'">\s]+)>/gi, t)).replace(/<(?:mailto:)?([-.\w]+@[-a-z0-9]+(\.[-a-z0-9]+)*\.[a-z]+)>/gi, o), 
    r.simplifiedAutoLink && (e = (e = e.replace(/\b(((https?|ftp|dict):\/\/|www\.)[^'">\s]+\.[^'">\s]+)(?=\s|$)(?!["<>])/gi, t)).replace(/(?:^|[ \n\t])([A-Za-z0-9!#$%&'*+-/=?^_`\{|}~\.]+@[-a-z0-9]+(\.[-a-z0-9]+)*\.[a-z]+)(?:$|[ \n\t])/gi, o)), 
    e = n.converter._dispatch("autoLinks.after", e, r, n);
}), showdown.subParser("blockGamut", function(e, r, n) {
    e = n.converter._dispatch("blockGamut.before", e, r, n), e = showdown.subParser("blockQuotes")(e, r, n), 
    e = showdown.subParser("headers")(e, r, n);
    var t = showdown.subParser("hashBlock")("<hr />", r, n);
    return e = (e = (e = e.replace(/^[ ]{0,2}([ ]?\*[ ]?){3,}[ \t]*$/gm, t)).replace(/^[ ]{0,2}([ ]?\-[ ]?){3,}[ \t]*$/gm, t)).replace(/^[ ]{0,2}([ ]?_[ ]?){3,}[ \t]*$/gm, t), 
    e = showdown.subParser("lists")(e, r, n), e = showdown.subParser("codeBlocks")(e, r, n), 
    e = showdown.subParser("tables")(e, r, n), e = showdown.subParser("hashHTMLBlocks")(e, r, n), 
    e = showdown.subParser("paragraphs")(e, r, n), e = n.converter._dispatch("blockGamut.after", e, r, n);
}), showdown.subParser("blockQuotes", function(e, t, o) {
    return e = (e = o.converter._dispatch("blockQuotes.before", e, t, o)).replace(/((^[ \t]{0,3}>[ \t]?.+\n(.+\n)*\n*)+)/gm, function(e, r) {
        var n = r;
        return n = (n = (n = n.replace(/^[ \t]*>[ \t]?/gm, "~0")).replace(/~0/g, "")).replace(/^[ \t]+$/gm, ""), 
        n = showdown.subParser("githubCodeBlocks")(n, t, o), n = (n = (n = showdown.subParser("blockGamut")(n, t, o)).replace(/(^|\n)/g, "$1  ")).replace(/(\s*<pre>[^\r]+?<\/pre>)/gm, function(e, r) {
            var n = r;
            return n = (n = n.replace(/^  /gm, "~0")).replace(/~0/g, "");
        }), showdown.subParser("hashBlock")("<blockquote>\n" + n + "\n</blockquote>", t, o);
    }), e = o.converter._dispatch("blockQuotes.after", e, t, o);
}), showdown.subParser("codeBlocks", function(e, a, i) {
    e = i.converter._dispatch("codeBlocks.before", e, a, i);
    return e = (e = (e += "~0").replace(/(?:\n\n|^)((?:(?:[ ]{4}|\t).*\n+)+)(\n*[ ]{0,3}[^ \t\n]|(?=~0))/g, function(e, r, n) {
        var t = r, o = n, s = "\n";
        return t = showdown.subParser("outdent")(t), t = showdown.subParser("encodeCode")(t), 
        t = (t = (t = showdown.subParser("detab")(t)).replace(/^\n+/g, "")).replace(/\n+$/g, ""), 
        a.omitExtraWLInCodeBlocks && (s = ""), t = "<pre><code>" + t + s + "</code></pre>", 
        showdown.subParser("hashBlock")(t, a, i) + o;
    })).replace(/~0/, ""), e = i.converter._dispatch("codeBlocks.after", e, a, i);
}), showdown.subParser("codeSpans", function(e, r, n) {
    return void 0 === (e = n.converter._dispatch("codeSpans.before", e, r, n)) && (e = ""), 
    e = e.replace(/(^|[^\\])(`+)([^\r]*?[^`])\2(?!`)/gm, function(e, r, n, t) {
        var o = t;
        return o = (o = o.replace(/^([ \t]*)/g, "")).replace(/[ \t]*$/g, ""), r + "<code>" + (o = showdown.subParser("encodeCode")(o)) + "</code>";
    }), e = n.converter._dispatch("codeSpans.after", e, r, n);
}), showdown.subParser("detab", function(e) {
    return e = (e = (e = (e = (e = e.replace(/\t(?=\t)/g, "    ")).replace(/\t/g, "~A~B")).replace(/~B(.+?)~A/g, function(e, r) {
        for (var n = r, t = 4 - n.length % 4, o = 0; o < t; o++) n += " ";
        return n;
    })).replace(/~A/g, "    ")).replace(/~B/g, "");
}), showdown.subParser("encodeAmpsAndAngles", function(e) {
    return e = (e = e.replace(/&(?!#?[xX]?(?:[0-9a-fA-F]+|\w+);)/g, "&amp;")).replace(/<(?![a-z\/?\$!])/gi, "&lt;");
}), showdown.subParser("encodeBackslashEscapes", function(e) {
    return e = (e = e.replace(/\\(\\)/g, showdown.helper.escapeCharactersCallback)).replace(/\\([`*_{}\[\]()>#+-.!])/g, showdown.helper.escapeCharactersCallback);
}), showdown.subParser("encodeCode", function(e) {
    return e = (e = (e = e.replace(/&/g, "&amp;")).replace(/</g, "&lt;")).replace(/>/g, "&gt;"), 
    e = showdown.helper.escapeCharacters(e, "*_{}[]\\", !1);
}), showdown.subParser("encodeEmailAddress", function(e) {
    var n = [ function(e) {
        return "&#" + e.charCodeAt(0) + ";";
    }, function(e) {
        return "&#x" + e.charCodeAt(0).toString(16) + ";";
    }, function(e) {
        return e;
    } ];
    return e = (e = '<a href="' + (e = (e = "mailto:" + e).replace(/./g, function(e) {
        if ("@" === e) e = n[Math.floor(2 * Math.random())](e); else if (":" !== e) {
            var r = Math.random();
            e = .9 < r ? n[2](e) : .45 < r ? n[1](e) : n[0](e);
        }
        return e;
    })) + '">' + e + "</a>").replace(/">.+:/g, '">');
}), showdown.subParser("escapeSpecialCharsWithinTagAttributes", function(e) {
    return e = e.replace(/(<[a-z\/!$]("[^"]*"|'[^']*'|[^'">])*>|<!(--.*?--\s*)+>)/gi, function(e) {
        var r = e.replace(/(.)<\/?code>(?=.)/g, "$1`");
        return r = showdown.helper.escapeCharacters(r, "\\`*_", !1);
    });
}), showdown.subParser("githubCodeBlocks", function(e, o, s) {
    return o.ghCodeBlocks ? (e = s.converter._dispatch("githubCodeBlocks.before", e, o, s), 
    e = (e = (e += "~0").replace(/(?:^|\n)```(.*)\n([\s\S]*?)\n```/g, function(e, r, n) {
        var t = o.omitExtraWLInCodeBlocks ? "" : "\n";
        return n = showdown.subParser("encodeCode")(n), n = "<pre><code" + (r ? ' class="' + r + " language-" + r + '"' : "") + ">" + (n = (n = (n = showdown.subParser("detab")(n)).replace(/^\n+/g, "")).replace(/\n+$/g, "")) + t + "</code></pre>", 
        n = showdown.subParser("hashBlock")(n, o, s), "\n\n~G" + (s.ghCodeBlocks.push({
            text: e,
            codeblock: n
        }) - 1) + "G\n\n";
    })).replace(/~0/, ""), s.converter._dispatch("githubCodeBlocks.after", e, o, s)) : e;
}), showdown.subParser("hashBlock", function(e, r, n) {
    return e = e.replace(/(^\n+|\n+$)/g, ""), "\n\n~K" + (n.gHtmlBlocks.push(e) - 1) + "K\n\n";
}), showdown.subParser("hashElement", function(e, r, t) {
    return function(e, r) {
        var n = r;
        return n = (n = (n = n.replace(/\n\n/g, "\n")).replace(/^\n/, "")).replace(/\n+$/g, ""), 
        n = "\n\n~K" + (t.gHtmlBlocks.push(n) - 1) + "K\n\n";
    };
}), showdown.subParser("hashHTMLBlocks", function(e, r, s) {
    for (var n = [ "pre", "div", "h1", "h2", "h3", "h4", "h5", "h6", "blockquote", "table", "dl", "ol", "ul", "script", "noscript", "form", "fieldset", "iframe", "math", "style", "section", "header", "footer", "nav", "article", "aside", "address", "audio", "canvas", "figure", "hgroup", "output", "video", "p" ], t = function(e, r, n, t) {
        var o = e;
        return -1 !== n.search(/\bmarkdown\b/) && (o = n + s.converter.makeHtml(r) + t), 
        "\n\n~K" + (s.gHtmlBlocks.push(o) - 1) + "K\n\n";
    }, o = 0; o < n.length; ++o) e = showdown.helper.replaceRecursiveRegExp(e, t, "^(?: |\\t){0,3}<" + n[o] + "\\b[^>]*>", "</" + n[o] + ">", "gim");
    return e = (e = (e = e.replace(/(\n[ ]{0,3}(<(hr)\b([^<>])*?\/?>)[ \t]*(?=\n{2,}))/g, showdown.subParser("hashElement")(e, r, s))).replace(/(<!--[\s\S]*?-->)/g, showdown.subParser("hashElement")(e, r, s))).replace(/(?:\n\n)([ ]{0,3}(?:<([?%])[^\r]*?\2>)[ \t]*(?=\n{2,}))/g, showdown.subParser("hashElement")(e, r, s));
}), showdown.subParser("hashHTMLSpans", function(e, r, n) {
    for (var t = showdown.helper.matchRecursiveRegExp(e, "<code\\b[^>]*>", "</code>", "gi"), o = 0; o < t.length; ++o) e = e.replace(t[o][0], "~L" + (n.gHtmlSpans.push(t[o][0]) - 1) + "L");
    return e;
}), showdown.subParser("unhashHTMLSpans", function(e, r, n) {
    for (var t = 0; t < n.gHtmlSpans.length; ++t) e = e.replace("~L" + t + "L", n.gHtmlSpans[t]);
    return e;
}), showdown.subParser("hashPreCodeTags", function(e, r, s) {
    return e = showdown.helper.replaceRecursiveRegExp(e, function(e, r, n, t) {
        var o = n + showdown.subParser("encodeCode")(r) + t;
        return "\n\n~G" + (s.ghCodeBlocks.push({
            text: e,
            codeblock: o
        }) - 1) + "G\n\n";
    }, "^(?: |\\t){0,3}<pre\\b[^>]*>\\s*<code\\b[^>]*>", "^(?: |\\t){0,3}</code>\\s*</pre>", "gim");
}), showdown.subParser("headers", function(e, i, l) {
    e = l.converter._dispatch("headers.before", e, i, l);
    var t = i.prefixHeaderId, c = isNaN(parseInt(i.headerLevelStart)) ? 1 : parseInt(i.headerLevelStart), r = i.smoothLivePreview ? /^(.+)[ \t]*\n={2,}[ \t]*\n+/gm : /^(.+)[ \t]*\n=+[ \t]*\n+/gm, n = i.smoothLivePreview ? /^(.+)[ \t]*\n-{2,}[ \t]*\n+/gm : /^(.+)[ \t]*\n-+[ \t]*\n+/gm;
    function h(e) {
        var r, n = e.replace(/[^\w]/g, "").toLowerCase();
        return l.hashLinkCounts[n] ? r = n + "-" + l.hashLinkCounts[n]++ : (r = n, l.hashLinkCounts[n] = 1), 
        !0 === t && (t = "section"), showdown.helper.isString(t) ? t + r : r;
    }
    return e = (e = (e = e.replace(r, function(e, r) {
        var n = showdown.subParser("spanGamut")(r, i, l), t = i.noHeaderId ? "" : ' id="' + h(r) + '"', o = "<h" + c + t + ">" + n + "</h" + c + ">";
        return showdown.subParser("hashBlock")(o, i, l);
    })).replace(n, function(e, r) {
        var n = showdown.subParser("spanGamut")(r, i, l), t = i.noHeaderId ? "" : ' id="' + h(r) + '"', o = c + 1, s = "<h" + o + t + ">" + n + "</h" + o + ">";
        return showdown.subParser("hashBlock")(s, i, l);
    })).replace(/^(#{1,6})[ \t]*(.+?)[ \t]*#*\n+/gm, function(e, r, n) {
        var t = showdown.subParser("spanGamut")(n, i, l), o = i.noHeaderId ? "" : ' id="' + h(n) + '"', s = c - 1 + r.length, a = "<h" + s + o + ">" + t + "</h" + s + ">";
        return showdown.subParser("hashBlock")(a, i, l);
    }), e = l.converter._dispatch("headers.after", e, i, l);
}), showdown.subParser("images", function(e, r, u) {
    function n(e, r, n, t, o, s, a, i) {
        var l = u.gUrls, c = u.gTitles, h = u.gDimensions;
        if (n = n.toLowerCase(), i || (i = ""), "" === t || null === t) {
            if ("" !== n && null !== n || (n = r.toLowerCase().replace(/ ?\n/g, " ")), t = "#" + n, 
            showdown.helper.isUndefined(l[n])) return e;
            t = l[n], showdown.helper.isUndefined(c[n]) || (i = c[n]), showdown.helper.isUndefined(h[n]) || (o = h[n].width, 
            s = h[n].height);
        }
        r = r.replace(/"/g, "&quot;"), r = showdown.helper.escapeCharacters(r, "*_", !1);
        var d = '<img src="' + (t = showdown.helper.escapeCharacters(t, "*_", !1)) + '" alt="' + r + '"';
        return i && (i = i.replace(/"/g, "&quot;"), d += ' title="' + (i = showdown.helper.escapeCharacters(i, "*_", !1)) + '"'), 
        o && s && (d += ' width="' + (o = "*" === o ? "auto" : o) + '"', d += ' height="' + (s = "*" === s ? "auto" : s) + '"'), 
        d += " />";
    }
    return e = (e = (e = u.converter._dispatch("images.before", e, r, u)).replace(/!\[([^\]]*?)] ?(?:\n *)?\[(.*?)]()()()()()/g, n)).replace(/!\[(.*?)]\s?\([ \t]*()<?(\S+?)>?(?: =([*\d]+[A-Za-z%]{0,4})x([*\d]+[A-Za-z%]{0,4}))?[ \t]*(?:(['"])(.*?)\6[ \t]*)?\)/g, n), 
    e = u.converter._dispatch("images.after", e, r, u);
}), showdown.subParser("italicsAndBold", function(e, r, n) {
    return e = n.converter._dispatch("italicsAndBold.before", e, r, n), e = r.literalMidWordUnderscores ? (e = (e = (e = e.replace(/(^|\s|>|\b)__(?=\S)([\s\S]+?)__(?=\b|<|\s|$)/gm, "$1<strong>$2</strong>")).replace(/(^|\s|>|\b)_(?=\S)([\s\S]+?)_(?=\b|<|\s|$)/gm, "$1<em>$2</em>")).replace(/(\*\*)(?=\S)([^\r]*?\S[*]*)\1/g, "<strong>$2</strong>")).replace(/(\*)(?=\S)([^\r]*?\S)\1/g, "<em>$2</em>") : (e = e.replace(/(\*\*|__)(?=\S)([^\r]*?\S[*_]*)\1/g, "<strong>$2</strong>")).replace(/(\*|_)(?=\S)([^\r]*?\S)\1/g, "<em>$2</em>"), 
    e = n.converter._dispatch("italicsAndBold.after", e, r, n);
}), showdown.subParser("lists", function(e, h, d) {
    function i(e, r) {
        d.gListLevel++, e = e.replace(/\n{2,}$/, "\n");
        var c = /\n[ \t]*\n(?!~0)/.test(e += "~0");
        return e = (e = e.replace(/(\n)?(^[ \t]*)([*+-]|\d+[.])[ \t]+((\[(x|X| )?])?[ \t]*[^\r]+?(\n{1,2}))(?=\n*(~0|\2([*+-]|\d+[.])[ \t]+))/gm, function(e, r, n, t, o, s, a) {
            a = a && "" !== a.trim();
            var i = showdown.subParser("outdent")(o, h, d), l = "";
            return s && h.tasklists && (l = ' class="task-list-item" style="list-style-type: none;"', 
            i = i.replace(/^[ \t]*\[(x|X| )?]/m, function() {
                var e = '<input type="checkbox" disabled style="margin: 0px 0.35em 0.25em -1.6em; vertical-align: middle;"';
                return a && (e += " checked"), e += ">";
            })), r || -1 < i.search(/\n{2,}/) ? (i = showdown.subParser("githubCodeBlocks")(i, h, d), 
            i = showdown.subParser("blockGamut")(i, h, d)) : (i = (i = showdown.subParser("lists")(i, h, d)).replace(/\n$/, ""), 
            i = c ? showdown.subParser("paragraphs")(i, h, d) : showdown.subParser("spanGamut")(i, h, d)), 
            i = "\n<li" + l + ">" + i + "</li>\n";
        })).replace(/~0/g, ""), d.gListLevel--, r && (e = e.replace(/\s+$/, "")), e;
    }
    function o(e, t, o) {
        var s = "ul" === t ? /^ {0,2}\d+\.[ \t]/gm : /^ {0,2}[*+-][ \t]/gm, r = [], a = "";
        if (-1 !== e.search(s)) {
            !function e(r) {
                var n = r.search(s);
                -1 !== n ? (a += "\n\n<" + t + ">" + i(r.slice(0, n), !!o) + "</" + t + ">\n\n", 
                s = "ul" === (t = "ul" === t ? "ol" : "ul") ? /^ {0,2}\d+\.[ \t]/gm : /^ {0,2}[*+-][ \t]/gm, 
                e(r.slice(n))) : a += "\n\n<" + t + ">" + i(r, !!o) + "</" + t + ">\n\n";
            }(e);
            for (var n = 0; n < r.length; ++n) ;
        } else a = "\n\n<" + t + ">" + i(e, !!o) + "</" + t + ">\n\n";
        return a;
    }
    e = d.converter._dispatch("lists.before", e, h, d), e += "~0";
    var r = /^(([ ]{0,3}([*+-]|\d+[.])[ \t]+)[^\r]+?(~0|\n{2,}(?=\S)(?![ \t]*(?:[*+-]|\d+[.])[ \t]+)))/gm;
    return d.gListLevel ? e = e.replace(r, function(e, r, n) {
        return o(r, -1 < n.search(/[*+-]/g) ? "ul" : "ol", !0);
    }) : (r = /(\n\n|^\n?)(([ ]{0,3}([*+-]|\d+[.])[ \t]+)[^\r]+?(~0|\n{2,}(?=\S)(?![ \t]*(?:[*+-]|\d+[.])[ \t]+)))/gm, 
    e = e.replace(r, function(e, r, n, t) {
        return o(n, -1 < t.search(/[*+-]/g) ? "ul" : "ol");
    })), e = e.replace(/~0/, ""), e = d.converter._dispatch("lists.after", e, h, d);
}), showdown.subParser("outdent", function(e) {
    return e = (e = e.replace(/^(\t|[ ]{1,4})/gm, "~0")).replace(/~0/g, "");
}), showdown.subParser("paragraphs", function(e, r, n) {
    for (var t = (e = (e = (e = n.converter._dispatch("paragraphs.before", e, r, n)).replace(/^\n+/g, "")).replace(/\n+$/g, "")).split(/\n{2,}/g), o = [], s = t.length, a = 0; a < s; a++) {
        var i = t[a];
        0 <= i.search(/~(K|G)(\d+)\1/g) || (i = (i = showdown.subParser("spanGamut")(i, r, n)).replace(/^([ \t]*)/g, "<p>"), 
        i += "</p>"), o.push(i);
    }
    for (s = o.length, a = 0; a < s; a++) {
        for (var l = "", c = o[a], h = !1; 0 <= c.search(/~(K|G)(\d+)\1/); ) {
            var d = RegExp.$1, u = RegExp.$2;
            l = (l = "K" === d ? n.gHtmlBlocks[u] : h ? showdown.subParser("encodeCode")(n.ghCodeBlocks[u].text) : n.ghCodeBlocks[u].codeblock).replace(/\$/g, "$$$$"), 
            c = c.replace(/(\n\n)?~(K|G)\d+\2(\n\n)?/, l), /^<pre\b[^>]*>\s*<code\b[^>]*>/.test(c) && (h = !0);
        }
        o[a] = c;
    }
    return e = (e = (e = o.join("\n\n")).replace(/^\n+/g, "")).replace(/\n+$/g, ""), 
    n.converter._dispatch("paragraphs.after", e, r, n);
}), showdown.subParser("runExtension", function(e, r, n, t) {
    if (e.filter) r = e.filter(r, t.converter, n); else if (e.regex) {
        var o = e.regex;
        !o instanceof RegExp && (o = new RegExp(o, "g")), r = r.replace(o, e.replace);
    }
    return r;
}), showdown.subParser("spanGamut", function(e, r, n) {
    return e = n.converter._dispatch("spanGamut.before", e, r, n), e = showdown.subParser("codeSpans")(e, r, n), 
    e = showdown.subParser("escapeSpecialCharsWithinTagAttributes")(e, r, n), e = showdown.subParser("encodeBackslashEscapes")(e, r, n), 
    e = showdown.subParser("images")(e, r, n), e = showdown.subParser("anchors")(e, r, n), 
    e = showdown.subParser("autoLinks")(e, r, n), e = showdown.subParser("encodeAmpsAndAngles")(e, r, n), 
    e = showdown.subParser("italicsAndBold")(e, r, n), e = (e = showdown.subParser("strikethrough")(e, r, n)).replace(/  +\n/g, " <br />\n"), 
    e = n.converter._dispatch("spanGamut.after", e, r, n);
}), showdown.subParser("strikethrough", function(e, r, n) {
    return r.strikethrough && (e = (e = n.converter._dispatch("strikethrough.before", e, r, n)).replace(/(?:~T){2}([\s\S]+?)(?:~T){2}/g, "<del>$1</del>"), 
    e = n.converter._dispatch("strikethrough.after", e, r, n)), e;
}), showdown.subParser("stripBlankLines", function(e) {
    return e.replace(/^[ \t]+$/gm, "");
}), showdown.subParser("stripLinkDefinitions", function(e, i, l) {
    return e = (e = (e += "~0").replace(/^ {0,3}\[(.+)]:[ \t]*\n?[ \t]*<?(\S+?)>?(?: =([*\d]+[A-Za-z%]{0,4})x([*\d]+[A-Za-z%]{0,4}))?[ \t]*\n?[ \t]*(?:(\n*)["|'(](.+?)["|')][ \t]*)?(?:\n+|(?=~0))/gm, function(e, r, n, t, o, s, a) {
        return r = r.toLowerCase(), l.gUrls[r] = showdown.subParser("encodeAmpsAndAngles")(n), 
        s ? s + a : (a && (l.gTitles[r] = a.replace(/"|'/g, "&quot;")), i.parseImgDimensions && t && o && (l.gDimensions[r] = {
            width: t,
            height: o
        }), "");
    })).replace(/~0/, "");
}), showdown.subParser("tables", function(e, g, b) {
    if (!g.tables) return e;
    return e = (e = b.converter._dispatch("tables.before", e, g, b)).replace(/^[ \t]{0,3}\|?.+\|.+\n[ \t]{0,3}\|?[ \t]*:?[ \t]*(?:-|=){2,}[ \t]*:?[ \t]*\|[ \t]*:?[ \t]*(?:-|=){2,}[\s\S]+?(?:\n\n|~0)/gm, function(e) {
        var r, n = e.split("\n");
        for (r = 0; r < n.length; ++r) /^[ \t]{0,3}\|/.test(n[r]) && (n[r] = n[r].replace(/^[ \t]{0,3}\|/, "")), 
        /\|[ \t]*$/.test(n[r]) && (n[r] = n[r].replace(/\|[ \t]*$/, ""));
        var t, o, s, a, i, l = n[0].split("|").map(function(e) {
            return e.trim();
        }), c = n[1].split("|").map(function(e) {
            return e.trim();
        }), h = [], d = [], u = [], p = [];
        for (n.shift(), n.shift(), r = 0; r < n.length; ++r) "" !== n[r].trim() && h.push(n[r].split("|").map(function(e) {
            return e.trim();
        }));
        if (l.length < c.length) return e;
        for (r = 0; r < c.length; ++r) u.push((t = c[r], /^:[ \t]*--*$/.test(t) ? ' style="text-align:left;"' : /^--*[ \t]*:[ \t]*$/.test(t) ? ' style="text-align:right;"' : /^:[ \t]*--*[ \t]*:$/.test(t) ? ' style="text-align:center;"' : ""));
        for (r = 0; r < l.length; ++r) showdown.helper.isUndefined(u[r]) && (u[r] = ""), 
        d.push((o = l[r], s = u[r], a = void 0, a = "", o = o.trim(), g.tableHeaderId && (a = ' id="' + o.replace(/ /g, "_").toLowerCase() + '"'), 
        "<th" + a + s + ">" + (o = showdown.subParser("spanGamut")(o, g, b)) + "</th>\n"));
        for (r = 0; r < h.length; ++r) {
            for (var w = [], f = 0; f < d.length; ++f) showdown.helper.isUndefined(h[r][f]), 
            w.push((i = h[r][f], "<td" + u[f] + ">" + showdown.subParser("spanGamut")(i, g, b) + "</td>\n"));
            p.push(w);
        }
        return function(e, r) {
            for (var n = "<table>\n<thead>\n<tr>\n", t = e.length, o = 0; o < t; ++o) n += e[o];
            for (n += "</tr>\n</thead>\n<tbody>\n", o = 0; o < r.length; ++o) {
                n += "<tr>\n";
                for (var s = 0; s < t; ++s) n += r[o][s];
                n += "</tr>\n";
            }
            return n += "</tbody>\n</table>\n";
        }(d, p);
    }), e = b.converter._dispatch("tables.after", e, g, b);
}), showdown.subParser("unescapeSpecialChars", function(e) {
    return e = e.replace(/~E(\d+)E/g, function(e, r) {
        var n = parseInt(r);
        return String.fromCharCode(n);
    });
}), module.exports = showdown;