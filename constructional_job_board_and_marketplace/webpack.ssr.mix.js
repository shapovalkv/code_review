const mix = require('laravel-mix')
const webpackNodeExternals = require('webpack-node-externals')
const webpackConfig = require('./webpack.config')

mix
  .options({ manifest: false })
  .js('resources/js/ssr.js', 'public/js')
  .vue({
      version: 3,
      options: { optimizeSSR: true },
      extractStyles: true,
      globalStyles: 'resources/assets/scss/_variables.scss',
  })
  .webpackConfig({
    ...webpackConfig,
    target: 'node',
    externals: [webpackNodeExternals()],
  })
