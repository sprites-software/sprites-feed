const path = require('path'),
      webpack = require('webpack'),
      ExtractTextPlugin = require("extract-text-webpack-plugin"),
      MinifyPlugin = require("babel-minify-webpack-plugin"),
      OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin'),
      WebpackBuildNotifierPlugin = require('webpack-build-notifier'),
      isProd = (process.env.NODE_ENV === 'production'),
      getPlugins = (isProd) => {
        let plugins = [];

        // Expose NODE_ENV
        plugins.push(new webpack.DefinePlugin({
          'process.env': {
            'NODE_ENV': process.env.NODE_ENV
          }
        }));

        // Add plugins for production
        if (isProd) {
          plugins.push(new webpack.optimize.UglifyJsPlugin());
          plugins.push(new OptimizeCssAssetsPlugin());
          plugins.push(new MinifyPlugin());
          plugins.push(new ExtractTextPlugin('[name].styles.css'));
        }

        // Add plugins for dev
        else {

          plugins.push(new WebpackBuildNotifierPlugin())
        }

        return plugins;
      },
      getLoaders = (isProd) => {
        let rules = []

        rules.push({
          test: /\.js$/,
          exclude: /(node_modules|bower_components)/,
          use: {
            loader: 'babel-loader',
            options: {
              "presets": ["env"]
            }
          }
        })
        rules.push({
          test: /\.jpg$/,
          use: {
            loader: "file-loader"
          }
        })
        rules.push({
          test: /\.png$/,
          use: {
            loader: "file-loader"
          }
        })
        rules.push({
          test: /\.svg$/,
          use: {
            loader: 'svg-url-loader',
            options: {}
          }
        })

        if(isProd){
          // Extract CSS in prod
          rules.push({
            test: /\.scss$/,
            use: ExtractTextPlugin.extract({
              fallbackLoader: 'style-loader',
              use: [
                'css-loader', 'postcss-loader', 'sass-loader'
              ]
            })
          })
        } else {
          rules.push({
            test: /\.scss$/,
            // Use CSS in JS for dev
            use: [{
              loader: 'style-loader', // inject CSS to page
            }, {
              loader: 'css-loader', // translates CSS into CommonJS modules
            }, {
              loader: 'postcss-loader', // Run post css actions
            }, {
              loader: 'sass-loader' // compiles SASS to CSS
            }]
          })
        }
        return rules
      }

console.log('\n\n\n\nTHIS IS A SPRITES SOFTWARE APPLICATION')
if(isProd){
  console.log('\nRunning in Production mode');
} else {
  console.log('\nRunning in Development mode');
}
console.log('\n\n');

module.exports = {
  entry: {
    'admin':'./admin/src/index.js',
    'page':'./page/src/index.js'
  },
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: '[name].bundle.js',
    //publicPath: isProd ? './' : './wp-content/themes/mireprezident/dist/'
  },
  module: {
    rules: getLoaders(isProd)
  },
  plugins: getPlugins(isProd)
};
