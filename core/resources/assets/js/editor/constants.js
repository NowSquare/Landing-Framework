var lfColors = ['ghost', 'dark-ghost', 'red', 'pink', 'purple', 'deep-purple', 'indigo', 'blue', 'light-blue', 'cyan', 'teal', 'green', 'light-green', 'lime', 'yellow', 'amber', 'orange', 'deep-orange', 'brown', 'grey', 'blue-grey'];

// Generate string of classes to easily use $.removeClass()
var lfBtnClasses = '';

for (var i = 0, len = lfColors.length; i < len; i++) {
  lfBtnClasses += 'btn-' + lfColors[i] + ' ';
}

for (var i = 0, len = lfColors.length; i < len; i++) {
  lfBtnClasses += 'btn-outline-' + lfColors[i] + ' ';
}

// Generate array of classes to easily use $.inArray()
var lfArrBtnClasses = [];

for (var i = 0, len = lfColors.length; i < len; i++) {
  lfArrBtnClasses.push('btn-' + lfColors[i]);
}

for (var i = 0, len = lfColors.length; i < len; i++) {
  lfArrBtnClasses.push('btn-outline-' + lfColors[i]);
}