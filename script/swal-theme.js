if (window.Swal) {
    const trackSwal = window.Swal.mixin({
        buttonsStyling: false,
        customClass: {
            container: 'track-swal-container',
            popup: 'track-swal-popup',
            title: 'track-swal-title',
            htmlContainer: 'track-swal-html',
            actions: 'track-swal-actions',
            confirmButton: 'track-swal-confirm',
            cancelButton: 'track-swal-cancel'
        }
    });

    window.Swal.fire = trackSwal.fire.bind(trackSwal);
}
