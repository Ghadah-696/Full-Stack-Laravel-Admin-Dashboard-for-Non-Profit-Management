import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


const subMenus = document.querySelectorAll('.has-submenu');

subMenus.forEach(menu => {
    const link = menu.querySelector('a');
    const submenu = menu.querySelector('.submenu');
    const iconElement = menu.querySelector('.toggle-icon i'); 

    // 1. التعامل مع حالة التحميل الأولي (تحديد display و transform)
    if (menu.classList.contains('active')) {
        submenu.style.display = 'block';
        if (iconElement) {
            iconElement.style.transform = 'rotate(-90deg)';
        }
    } else {
        submenu.style.display = 'none';
        if (iconElement) {
            iconElement.style.transform = 'rotate(0deg)';
        }
    }

    link.addEventListener('click', (e) => {
        e.preventDefault();

        // 2. إغلاق القوائم الأخرى
        subMenus.forEach(m => {
            if (m !== menu) {
                m.classList.remove('active');
                m.querySelector('.submenu').style.display = 'none';
                
                const otherIcon = m.querySelector('.toggle-icon i');
                if (otherIcon) {
                    otherIcon.style.transform = 'rotate(0deg)';
                }
            }
        });

        // 3. تبديل القائمة الحالية (الاعتماد على display)
        const isHidden = submenu.style.display === 'none';
        
        if (isHidden) {
            submenu.style.display = 'block';
            if (iconElement) {
                iconElement.style.transform = 'rotate(-90deg)';
            }
            menu.classList.add('active');
        } else {
            submenu.style.display = 'none';
            if (iconElement) {
                iconElement.style.transform = 'rotate(0deg)';
            }
            menu.classList.remove('active');
        }
    });
});