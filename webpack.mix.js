let mix = require('laravel-mix');
require('mix-env-file');

const THEME_NAME = process.env.MIX_THEME_NAME;
const EXTENSION_NAME = process.env.MIX_EXTENSION_NAME;
const PROXY_URL = process.env.MIX_BROWSERSYNC_PROXY_URL;
const SOURCE_PATH = process.env.MIX_SOURCE_PATH;
const OPENCART_PATH = process.env.MIX_OPENCART_PATH;
let outputPath = OPENCART_PATH;

mix.options({
  processCssUrls: false
});

mix.disableSuccessNotifications();
mix.setPublicPath('./');

mix.browserSync({
  proxy: PROXY_URL,
  open: false,
  notify: false,
  files: [
    SOURCE_PATH + '/**/*'
  ]
});

if (mix.inProduction()) {
  outputPath = './build/';

  mix
    .js(SOURCE_PATH + 'scripts/admin.js', outputPath + 'upload/admin/view/javascript/' + EXTENSION_NAME + '.js')
    .postCss(SOURCE_PATH + 'css/admin.css', outputPath + 'upload/admin/view/stylesheet/' + EXTENSION_NAME + '.css')
    .copyDirectory(SOURCE_PATH + 'admin/', outputPath + 'upload/admin/')
    .js(SOURCE_PATH + 'scripts/catalog.js', outputPath + 'upload/catalog/view/javascript/' + EXTENSION_NAME + '.js')
    .postCss(SOURCE_PATH + 'css/catalog.css', outputPath + 'upload/catalog/view/theme/' + THEME_NAME + '/stylesheet/' + EXTENSION_NAME + '.css')
    .copyDirectory(SOURCE_PATH + 'catalog/', outputPath + 'upload/catalog/')
    .copy(SOURCE_PATH + 'modifier/install.xml', outputPath + 'install.xml')
    .copyDirectory(SOURCE_PATH + 'static/', outputPath + 'upload/');
} else {
  mix
    .js(SOURCE_PATH + 'scripts/admin.js', outputPath + 'admin/view/javascript/' + EXTENSION_NAME + '.js')
    .postCss(SOURCE_PATH + 'css/admin.css', outputPath + 'admin/view/stylesheet/' + EXTENSION_NAME + '.css')
    .copyDirectory(SOURCE_PATH + 'admin/', outputPath + 'admin/')
    .js(SOURCE_PATH + 'scripts/catalog.js', outputPath + 'catalog/view/javascript/' + EXTENSION_NAME + '.js')
    .postCss(SOURCE_PATH + 'css/catalog.css', outputPath + 'catalog/view/theme/' + THEME_NAME + '/stylesheet/' + EXTENSION_NAME + '.css')
    .copyDirectory(SOURCE_PATH + 'catalog/', outputPath + 'catalog/')
    .copy(SOURCE_PATH + 'modifier/install.xml', outputPath + 'system/' + EXTENSION_NAME + '.ocmod.xml')
    .copyDirectory(SOURCE_PATH + 'static/', outputPath);
}
