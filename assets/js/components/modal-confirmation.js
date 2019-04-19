import bootbox from 'bootbox';

export class ModalConfirmation {
    buildModalWithEvent(event) {
        bootbox.confirm({
            size: "small",
            message: "Are you sure ?",
            callback: (result) => {
                if(result) {
                    window.location = document.location.origin + event.target.getAttribute('data-href');
                }
            }
        });
    };
};