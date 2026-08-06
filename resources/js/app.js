import Chart from 'chart.js/auto';
window.Chart = Chart;

const sidebar=document.getElementById('sidebar'),overlay=document.getElementById('overlay');
document.getElementById('menuButton')?.addEventListener('click',()=>{sidebar.classList.toggle('-translate-x-full');overlay.classList.toggle('hidden')});
overlay?.addEventListener('click',()=>{sidebar.classList.add('-translate-x-full');overlay.classList.add('hidden')});
document.querySelectorAll('[data-modal]').forEach(b=>b.addEventListener('click',()=>{const m=document.getElementById(b.dataset.modal);m?.classList.add('open');m?.setAttribute('aria-hidden','false')}));
document.querySelectorAll('[data-close-modal]').forEach(b=>b.addEventListener('click',()=>b.closest('.modal')?.classList.remove('open')));
document.querySelectorAll('.modal').forEach(m=>m.addEventListener('click',e=>{if(e.target===m)m.classList.remove('open')}));
document.querySelector('[data-toggle-password]')?.addEventListener('click',e=>{const p=document.getElementById('password');p.type=p.type==='password'?'text':'password';e.target.textContent=p.type==='password'?'Lihat':'Tutup'});
const rows=document.getElementById('itemRows');document.getElementById('addItem')?.addEventListener('click',()=>{const row=rows.querySelector('.item-row').cloneNode(true);row.querySelector('select').value='';row.querySelector('input').value=1;rows.appendChild(row)});rows?.addEventListener('click',e=>{if(e.target.classList.contains('remove-row')&&rows.children.length>1)e.target.closest('.item-row').remove()});
