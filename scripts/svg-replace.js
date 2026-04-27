const fs = require('fs');
const path = 'd:/Xamp-7.1.26/xampp/htdocs/FRONTEND-SOCIOECONOMICO/assets/svg/FyA-logo.svg';
let s = fs.readFileSync(path, 'utf8');

s = s.replace('<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"', '<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" fill="currentColor"');
s = s.replace(/style="fill:#d20a11"/g, '');
s = s.replace(/style="fill:#fff;fill-rule:nonzero"/g, 'fill-rule="nonzero"');

fs.writeFileSync(path, s, 'utf8');
console.log('svg replacements applied');
