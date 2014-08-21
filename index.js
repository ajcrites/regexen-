var dom = require("fs").readFileSync("sample.html"),
    jsdom = require("jsdom").jsdom,
    doc = jsdom("<html><body>" + dom + "</body></html>");

Array.prototype.forEach.call(doc.querySelectorAll("a"), function (elem) {
    console.log(elem.getAttribute("href") || "");
});
