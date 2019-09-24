(function(d3) {

    d3.sineWave = function() {
        var amplitude = 1;
        var wavelength = 2;
        var phase = 2
        var samplesCount = 20;
        var xTransform = function(d) { return d; };
        var yTransform = function(d) { return d; };
            
        function sineWave() { }
    
        sineWave.amplitude = function(_) {
            if (!arguments.length) return amplitude;
            amplitude = _;
            return sineWave;
        }
    
        sineWave.wavelength = function(_) {
            if (!arguments.length) return wavelength;
            wavelength = _;
            return sineWave;
        }
    
        sineWave.phase = function(_) {
            if (!arguments.length) return phase;
            phase = _;
            return sineWave;
        }
    
        sineWave.samplesCount = function(_) {
            if (!arguments.length) return samplesCount;
            samplesCount = _;
            return sineWave;
        }
    
        /**
         * Transforms x value post calculation. Transformation function is passed a x value that ranges between 0 and 1 inclusively.
         * @param {function} _ Transforming function
         */
        sineWave.xTransform = function(_) {
            if (!arguments.length) return xTransform;
            xTransform = _;
            return sineWave;
        }
    
        /**
         * Transforms y value post calculation. Transformation function reflects sine parameters. If amplitude is 1 values will range from -1 to 1.
         * @param {function} _ Transforming function
         */
        sineWave.yTransform = function(_) {
            if (!arguments.length) return yTransform;
            yTransform = _;
            return sineWave;
        }
    
        sineWave.samples = function() {
            return d3.range(samplesCount).map(function(d) { 
                var t = d / (samplesCount - 1);
                var y = Math.sin(2 * Math.PI * t * wavelength + phase) * amplitude;
                return [xTransform(t), yTransform(y)];
            });
        }
        
        return sineWave;
    }

}(window.d3 ));