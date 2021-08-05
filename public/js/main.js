/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

window.onload = function() {
    var rows = document.getElementsByClassName('api-token-row__inner');
    var tooltips = document.getElementsByClassName('api-token-row__tooltip');
    Array.prototype.forEach.call(rows, function(row, idx) {
        row.addEventListener('mouseover', function() {
            tooltips[idx].classList.remove('api-token-row__tooltip--hidden');
            tooltips[idx].classList.add('api-token-row__tooltip--visible');
        });
        row.addEventListener('mouseout', function() {
            tooltips[idx].classList.remove('api-token-row__tooltip--visible');
            tooltips[idx].classList.add('api-token-row__tooltip--hidden');
        });
        row.addEventListener('click', function() {
            navigator.clipboard.writeText(row.innerHTML)
                .then(() => {
                    alert('copied token!');
                })
                .catch(err => {
                    console.log('error occurred.')
                });
        });
    });
}
