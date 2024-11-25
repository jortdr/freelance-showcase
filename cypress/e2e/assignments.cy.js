describe('Assignments', () => {
    beforeEach(() => {
        cy.visit('http://freelance-website.test/login');
        cy.get('input[type="email"]').type('test@example.com');
        cy.get('input[type="password"]').type('password');
        cy.contains('button', 'Log in').click();
        cy.contains('Dashboard');
    });

    let assignmentUrl = '';

    it('should create an assignment', () => {
        cy.visit('http://freelance-website.test/assignments/create');
        cy.get('#title').type('Test Assignment');
        cy.get('#description').type('This is a test assignment.');
        cy.get('#budget > .p-inputtext').type('100');
        cy.contains('button', 'Create').click();
        cy.wait(5000);

        //     get the url that was redirected to
        cy.url().then(url => {
            assignmentUrl = url;
        });
    });

    it('should update the assignment', () => {
        cy.visit(assignmentUrl);
        cy.contains('button', 'Edit').click();
        cy.get('#title').type(' Updated');
        cy.contains('button', 'Update').click();

        //check that the url is still the same
        cy.url().should('eq', assignmentUrl + '/edit');

        cy.wait(5000);

        cy.url().should('eq', assignmentUrl);
    });

    it('should send a chat message', () => {
        cy.visit(assignmentUrl);
        cy.get('.flex-1').type('This is a test message.');
        cy.contains('button', 'Send').click();
        cy.contains('This is a test message.');
    });

});
