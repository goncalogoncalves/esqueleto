function printElement(elem) {
    popup($(elem).html());
}

function popup(data) {
    var title = typeof WEBSITE_NAME !== 'undefined' ? WEBSITE_NAME : "Print";

    var mywindow = window.open('', title, 'height=700,width=1100');
    mywindow.document.write('<html><head><title>' + title + '</title>');
    mywindow.document.write('</head><body >');
    mywindow.document.write(data);
    mywindow.document.write('</body></html>');

    mywindow.document.close();
    mywindow.focus();

    mywindow.print();
    mywindow.close();

    return true;
}
