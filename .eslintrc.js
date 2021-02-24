module.exports = {
  root: true,

  extends: [
    // Uncomment the next line if this project uses Vue.
    // 'plugin:vue/essential',
    // This should come last unless there's a specific reason to override our config with another.
    '@highlandsolutions/highland',
  ],

  env: {
    browser: true,
  },

  // Add any global functions/values here.
  globals: {
    // E.g. route() is a helper that enables referencing Laravel-defined routes in javascript.
    // https://journal.highlandsolutions.com/how-i-like-to-simplify-ziggys-route-helper-59127e19d4ba
    // route: true,
  },

  settings: {
    // If you have issues with the import plugin's reolution, try uncommenting and tweaking this.
    // https://github.com/benmosher/eslint-plugin-import
    // 'import/resolver': {
    //   node: {
    //     extensions: [
    //       '.js',
    //       '.vue',
    //     ],
    //     paths: [
    //       'resources/js',
    //       'vendor',
    //     ],
    //   },
    // },
  },
};
