const importAll = r => r.keys().forEach(r),
      ready = (fn) => (document.readyState != 'loading') ? fn() : document.addEventListener('DOMContentLoaded', fn)


if(!NodeList.prototype.forEach){
  NodeList.prototype.forEach = Array.prototype.forEach
}

importAll(require.context("./stylesheets/", true, /\.scss$/))

ready(()=>{
  require("./components/service.js")
})
