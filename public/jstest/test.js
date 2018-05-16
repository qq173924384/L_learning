$.fn.extend({
    getContext: function(str) {
        if (this.length == 1) {
            return this[0].getContext(str);
        } else {
            return false;
        }
    },
    drawImage: function(img) {
        var ctx = this.getContext("2d");
        if (ctx === false) {
            return false;
        } else {
            var img = img.get(0);
            height = img.naturalHeight;
            width = img.naturalWidth;
            this[0].height = height;
            this[0].width = width;
            return ctx.drawImage(img, 0, 0);
        }
    },
    getImageData: function(x, y, width, height) {
        var ctx = this.getContext("2d");
        if (ctx === false) {
            return false;
        } else {
            return ctx.getImageData(x, y, width, height);
        }
    },
    putImageData: function(img_data, x, y) {
        var ctx = this.getContext("2d");
        if (ctx === false) {
            return false;
        } else {
            return ctx.putImageData(img_data, x, y);
        }
    }
});
$.extend({
    ImageData: function(image_data) {
        image_data.getPixel = function(x, y) {
            var i = y * 4 * this.width + 4 * x;
            return {
                r: this.data[i],
                g: this.data[i + 1],
                b: this.data[i + 2],
                alpha: this.data[i + 3]
            }
        }
        image_data.setPixel = function(x, y, pixel) {
            var i = y * 4 * this.width + 4 * x;
            this.data[i] = pixel.r;
            this.data[i + 1] = pixel.g;
            this.data[i + 2] = pixel.b;
            this.data[i + 3] = pixel.alpha;
            return this;
        }
        return image_data;
    }
});
$(document).ready(function() {
    var start = new Date().getTime();
    var img = $('#img');
    var canvas = $('#canvas');
    canvas.drawImage(img);
    var img_data = $.ImageData(canvas.getImageData(0, 0, width, height));
    for (var img_y = 0; img_y < img_data.height; img_y++) {
        for (var img_x = 0; img_x < img_data.width; img_x++) {
            var pixel = img_data.getPixel(img_x, img_y);
            pixel.r = 255 - pixel.r;
            pixel.g = 255 - pixel.g;
            pixel.b = 255 - pixel.b;
            img_data.setPixel(img_x, img_y, pixel)
        }
    }
    canvas.putImageData(img_data, 0, 0);
    var end = new Date().getTime();
    console.log((end - start) + "ms")
})