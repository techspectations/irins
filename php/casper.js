var casper = require('casper').create({
    verbose: true,
    logLevel: 'debug',
    colorizerType: 'Dummy'
});

casper.userAgent('Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36');
casper.start().then(function() {
    this.open('https://www.unionbankonline.co.in/', {
        headers: {
            'Accept': 'text/html'
        }
    });
});
casper.then(function() {
    this.capture('/var/www/html/images/ubank.png', {
        top: 0,
        left: 0,
        width: 800,
        height: 600
    });
});
casper.then(function () {
    this.echo('[CURRENT_URL]' + this.getCurrentUrl());
    this.echo('[CURRENT_TITLE]' + this.getTitle());
    this.echo('[CURRENT_PAGE_CONTENT]' + this.getPageContent().replace(new RegExp('\r?\n','g'), ''));
    this.echo('[CURRENT_HTML]' + this.getHTML().replace(new RegExp('\r?\n','g'), ''));
});

casper.run();
