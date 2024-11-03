// Preloader area
const preloader = document.getElementById("preloader");
const preloaderFunction = () => {
    preloader.style.display = "none";
};
// toggleSideMenu start
const toggleSideMenu = () => {
    document.body.classList.toggle("toggle-sidebar");
};
// toggleSideMenu end

// input file preview
const previewImage = (id) => {
    document.getElementById(id).src = URL.createObjectURL(event.target.files[0]);
};

$(function() {
    $('.btn-1, .btn-2')
        .on('mouseenter', function(e) {
            var parentOffset = $(this).offset(),
                relX = e.pageX - parentOffset.left,
                relY = e.pageY - parentOffset.top;
            $(this).find('span').css({top:relY, left:relX})
        })
        .on('mouseout', function(e) {
            var parentOffset = $(this).offset(),
                relX = e.pageX - parentOffset.left,
                relY = e.pageY - parentOffset.top;
            $(this).find('span').css({top:relY, left:relX})
        });
});
$(function() {
    $('.btn-2')
        .on('mouseenter', function(e) {
            var parentOffset = $(this).offset(),
                relX = e.pageX - parentOffset.left,
                relY = e.pageY - parentOffset.top;
            $(this).find('span').css({top:relY, left:relX})
        })
        .on('mouseout', function(e) {
            var parentOffset = $(this).offset(),
                relX = e.pageX - parentOffset.left,
                relY = e.pageY - parentOffset.top;
            $(this).find('span').css({top:relY, left:relX})
        });
});


// Tooltip
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

// cmn select2 start
$(document).ready(function () {
    $('.cmn-select2').select2();
});

// cmn select2 end

document.addEventListener('DOMContentLoaded', function () {
// cmn-select2-modal
    $(".modal-select").select2({
        dropdownParent: $("#formModal"),
    });
});

// cmn-select2 with image start
$(document).ready(function () {
    $('.cmn-select2-image').select2({
        templateResult: formatState,
        templateSelection: formatState
    });
});

// select2 function
function formatState(state) {
    if (!state.id) {
        return state.text;
    }
    var baseUrl = "assets/img/mini-flag";
    var $state = $(
        '<span><img src="' + baseUrl + '/' + state.element.value.toLowerCase() + '.svg" class="img-flag" /> ' + state.text + '</span>'
    );
    return $state;
};
// cmn-select2 with image start


$(document).ready(function () {
    // owl carousel dashboard card
    $('.carousel-1').owlCarousel({
        loop: true,
        // autoplay: true,
        margin: -20,
        nav: false,
        dots: false,
        // rtl:true,
        responsive: {
            0: {
                items: 1
            },
            576: {
                items: 2
            },
            768: {
                items: 3
            }
        }
    });

});

document.addEventListener('DOMContentLoaded', function () {
    const dropdownItems = document.querySelectorAll('#sidebar-bottom .dropdown-item');
    dropdownItems.forEach((item) => {
        item.addEventListener('click', () => {
            const selectedImageSrc = item.querySelector('img').getAttribute('src');
            const selectedTitle = item.querySelector('.title').textContent;
            const dropdownToggle = document.querySelector('#sidebar-bottom .dropdown-toggle');
            dropdownToggle.querySelector('img').setAttribute('src', selectedImageSrc);
            dropdownToggle.querySelector('.title').textContent = selectedTitle;
        });
    });
});

// filter messages
document.addEventListener('DOMContentLoaded', function () {
    // show the whisper box
    const whisper = document.getElementById("whisperList");

    if (whisper) {
        const whisperToggle = document.getElementById('whisperToggle');
        const replyToggle = document.getElementById('replyToggle');
        const closeBtn = document.getElementById('closeBtn');
        const selectedInputField = document.getElementById('exampleFormControlTextarea1');
        const whisperBox = document.querySelector('.whisper-box');
        const messages = Array.from(whisper.getElementsByTagName("li")).map(li => li.getAttribute('data-msg'));
        const searchInput = document.getElementById('searchInput');
        const whisperList = document.getElementById('whisperList');

        whisperToggle.addEventListener('click', (e) => {
            e.preventDefault();
            whisperBox.style.display = whisperBox.style.display === 'block' ? 'none' : 'block';
        });
        replyToggle.addEventListener('click', (e) => {
            e.preventDefault();
            whisperBox.style.display = 'none';
        });
        closeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            whisperBox.style.display = 'none';
        });


        function filterMessages() {
            const searchTerm = searchInput.value.toLowerCase();
            whisperList.innerText = '';
            messages
                .filter(searchText => searchText.toLowerCase().includes(searchTerm))
                .forEach(searchText => {
                    const li = document.createElement('li');
                    li.textContent = searchText;
                    li.addEventListener('click', () => {
                        selectedInputField.value = searchText.split(':').pop().trim();
                        selectedInputField.textContent = searchText.split(':').pop().trim();
                    });
                    whisperList.appendChild(li);
                });
        }

        searchInput.addEventListener('input', filterMessages);

        // Initialize with all countries
        filterMessages();
    }
});

document.addEventListener('DOMContentLoaded', function () {

    const notes = document.getElementById("noteList");
    if (notes) {
        const note_title = document.getElementById("note_title");
        const note_details = document.getElementById("note_details");
        const noteMessages = Array.from(notes.getElementsByTagName("li")).map(li => ({
            msg: li.getAttribute('data-msg'),
            details: li.getAttribute('data-details')
        }));
        const noteSearchInput = document.getElementById('noteSearchInput');
        const noteList = document.getElementById('noteList');

        function filterMessagesNote() {
            const searchTerm = noteSearchInput.value.toLowerCase();
            noteList.innerText = '';
            noteMessages
                .filter(note => note.msg.toLowerCase().includes(searchTerm))
                .forEach(note => {
                    const li = document.createElement('li');
                    li.textContent = note.msg;
                    li.addEventListener('click', () => {
                        note_title.innerText = note.msg;
                        note_details.innerHTML = note.details;
                    });
                    noteList.appendChild(li);
                });
        }

        noteSearchInput.addEventListener('input', filterMessagesNote);

        filterMessagesNote();
    }

});

$(document).on('click', '.skltbs-tab', function() {
    let targetId = $(this).data('target');

    // Hide all panels
    $('.skltbs-panel').addClass('d-none');

    // Remove 'active' class from all tabs
    $('.skltbs-tab').removeClass('active');

    // Show the target panel
    $(`#${targetId}`).removeClass('d-none');

    // Add 'active' class to the clicked tab
    $(this).addClass('active');
});


