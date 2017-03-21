module.exports = {
    // stops event propagation
    stopEventPropagation: function (event) {
        event = event || window.event; // cross-browser event
        if (event.stopPropagation) {
            // W3C standard variant
            event.stopPropagation();
        } else {
            // IE variant
            event.cancelBubble = true;
        }
    }
};

