'use strict'
const openModalClassList = document.querySelectorAll('.modal-open')
const closeModalClassList = document.querySelectorAll('.modal-close')
const overlay = document.querySelector('.modal-overlay')
const body = document.querySelector('body')
const modal = document.querySelector('.modal')
const modalInnerHTML = document.getElementById('modalInner')

for (let i = 0; i < openModalClassList.length; i++) {
    openModalClassList[i].addEventListener('click', (e) => {
        // form要素に送信先が指定されていない場合、現在のURLに対してフォームの内容を送信するのを阻止。
        e.preventDefault()
        let eventId = parseInt(e.currentTarget.id.replace('event-', ''))
        openModal(eventId)
        console.log(eventId);
    }, false)
}

for (var i = 0; i < closeModalClassList.length; i++) {
    closeModalClassList[i].addEventListener('click', closeModal)
}

overlay.addEventListener('click', closeModal)

async function openModal(eventId) {
    try {
        const url = '/api/getModalInfo.php?eventId=' + eventId
        const res = await fetch(url)
        const event = await res.json()
        var status_color = "";
        switch (Number(event.status_id)) {
            case 0:
                status_color = "yellow";
                break;
            case 1:
                status_color = "green";
                break;
            case 2:
                status_color = "gray";
                break;

            default:
                break;
        }
        let modalHTML = `
            <h2 class="text-md font-bold mb-3">${event.name}</h2>
            <p class="text-sm">${event.date}（${event.day_of_week}）</p>
            <p class="text-sm">${event.start_at} ~ ${event.end_at}</p>

            <hr class="my-4">

            <p class="text-md">
                ${event.message}
            </p>
            <p class="text-md">
                【詳細】<br>
                ${event.event_detail}
            </p>
            <hr class="my-4">
            <p class="text-sm"><span class="text-xl">${event.total_participants ?? 0}</span>人参加 ></p>
            `

        modalHTML += `
            <div class="text-center mt-6">
                <p class="text-lg font-bold text-${status_color}-400">${event.status}</p>
                <p class="text-xs text-${status_color}-400">期限 ${event.deadline}</p>
            </div>
            <form action="/participation_management.php" method="post">
                <div class="flex mt-5">
                <button type='submit' name="status_id" value="1" class="participation flex-1 py-2 mx-3 rounded-3xl text-white text-lg font-bold" id="modal__participation">参加する</button>
                <button type='submit' name="status_id" value="2" class="participation flex-1 py-2 mx-3 rounded-3xl text-white text-lg font-bold" id="modal__nonParticipation">参加しない</button>
                <input type="hidden" name="user_id" value="${event.user_id}">
                <input type="hidden" name="event_id" value="${eventId}">
                </div>
            </form>`

        modalInnerHTML.insertAdjacentHTML('afterbegin', modalHTML)
        if (event.status_id == 1) {
            document.getElementById("modal__participation").classList.add("cantClick");
            document.getElementById("modal__nonParticipation").classList.remove("cantClick");
            document.getElementById("modal__participation").classList.add("non__participation");
            document.getElementById("modal__nonParticipation").classList.remove("non__participation");
            document.getElementById("modal__participation").disabled = true;
            document.getElementById("modal__nonParticipation").disabled = false;
        } else if (event.status_id == 2) {
            document.getElementById("modal__participation").classList.remove("cantClick");
            document.getElementById("modal__nonParticipation").classList.add("cantClick");
            document.getElementById("modal__participation").classList.remove("non__participation");
            document.getElementById("modal__nonParticipation").classList.add("non__participation");
            document.getElementById("modal__nonParticipation").disabled = true;
            document.getElementById("modal__participation").disabled = false;
        } else if (event.status_id == 0) {
            document.getElementById("modal__participation").classList.add("participation");
            document.getElementById("modal__nonParticipation").classList.add("participation");
        }
    } catch (error) {
        console.log(error)
    }
    toggleModal()
}

function closeModal() {
    modalInnerHTML.innerHTML = ''
    toggleModal()
}

function toggleModal() {
    modal.classList.toggle('opacity-0')
    modal.classList.toggle('pointer-events-none')
    body.classList.toggle('modal-active')
}

async function participateEvent(eventId) {
    try {
        let formData = new FormData();
        formData.append('eventId', eventId);
        const url = '/api/postEventAttendance.php'
        await fetch(url, {
            method: 'POST',
            body: formData
        }).then((res) => {
            if (res.status !== 200) {
                throw new Error("system error");
            }
            return res.text();
        })
        closeModal()
        location.reload()
    } catch (error) {
        console.log(error)
    }
}

/*------------------------------
* アコーディオン
-------------------------------*/
$(function() {
    $('.js-menu__item__link').each(function() {
        $(this).on('click', function() {
            $("+.submenu", this).slideToggle();
            return false;
        });
    });
});