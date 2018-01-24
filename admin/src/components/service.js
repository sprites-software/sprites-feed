const checkbox = document.getElementById('sfs-enable-service'),
      global = document.querySelector('.Global'),
      disable = () => {
        global.classList.add('apiDisabled')
      },
      enable = () => {
        global.classList.remove('apiDisabled')
      }

if (!!checkbox && !!global) {
  if(!checkbox.checked) disable()

  checkbox.addEventListener('change',()=>{
    if(!checkbox.checked) disable()
    else enable()
  })
}
