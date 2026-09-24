function loadSidebar(){
  const mount=document.getElementById('sidebarMount');
  if(!mount)return;
  fetch('sidebar.php').then(r=>r.text()).then(html=>{
    mount.innerHTML=html;
    const page=document.body.dataset.page;
    document.querySelectorAll('#farmerNav a[data-page]').forEach(a=>{
      if(a.dataset.page===page)a.classList.add('active');
    });
  }).catch(()=>{
    mount.innerHTML='<aside class="sidebar"><div class="brand"><div class="brand-logo">H</div><div><h2>Farmer Portal</h2><p>Manage your harvest</p></div></div><p class="muted" style="padding:12px">Sidebar could not be loaded.</p></aside>';
  });
}
function demoMessage(id,msg){
  const el=document.getElementById(id);if(el){
    el.textContent=msg;el.className='notice success';
  }
}

function money(n){
  return 'Rs. '+Number(n).toLocaleString('en-LK',{
    minimumFractionDigits:2,maximumFractionDigits:2
  });
}

function setOrderStatus(status){
  const el=document.getElementById('orderStatus');if(el)el.textContent=status;demoMessage('orderActionMsg','Demo update: order status changed to '+status+'. Backend persistence is not connected yet.');
}

function markAllNotificationsRead(){
  document.querySelectorAll('.notification-item.unread').forEach(x=>x.classList.remove('unread'));const c=document.getElementById('unreadCount');if(c)c.textContent='0';demoMessage('notificationMsg','All notifications are marked as read in this frontend demo.');
}