import './bootstrap';
import './api';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Swal = Swal;
window.Alpine = Alpine;


window.confirmDelete = function (formId) {
    Swal.fire({
        title: 'Bạn có chắc chắn không?',
        text: "Hành động này không thể hoàn tác!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Xóa ngay!',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('delete-form-' + formId);
            if(form) form.submit();
        }
    });
};


window.searchAutocomplete = function () {
    return {
        open: false,
        suggestions: [],
        timeout: null,
        search(query) {
            clearTimeout(this.timeout);

            if (!query || query.length < 2) {
                this.suggestions = [];
                this.open = false;
                return;
            }

            this.timeout = setTimeout(() => {
                
                fetch(`/api/games/search-suggestions?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        this.suggestions = data;
                        this.open = data.length > 0;
                    })
                    .catch(err => {
                        console.error('Search error:', err);
                        this.suggestions = [];
                        this.open = false;
                    });
            }, 300);
        },
        closeSuggestions() {
            this.open = false;
        }
    };
};

Alpine.start();
