function showHide() {
  let passwordField = document.getElementById('pass');
  let icon = document.getElementById('toggle_password');

  // 确保密码字段存在
  if (!passwordField) {
      console.error('Password field not found');
      return;
  }

  // 确保图标存在
  if (!icon) {
      console.error('Icon not found');
      return;
  }

  if (passwordField.type === 'password') {
      passwordField.type = 'text';
      icon.src = getIconPath('close');
  } else {
      passwordField.type = 'password';
      icon.src = getIconPath('open');
  }
}

// 获取图标路径的函数
function getIconPath(type) {
  const basePath = './svg/';
  if (type === 'close') {
      return basePath + 'preview-close-one.svg';
  } else if (type === 'open') {
      return basePath + 'preview-open.svg';
  }
  return '';
}