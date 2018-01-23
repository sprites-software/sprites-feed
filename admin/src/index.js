const importAll = r => r.keys().forEach(r)

if(!NodeList.prototype.forEach){
  NodeList.prototype.forEach = Array.prototype.forEach
}

importAll(require.context("./stylesheets/", true, /\.scss$/))
