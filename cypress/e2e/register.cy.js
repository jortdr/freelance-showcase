describe('Register', () => {
    it('should not register a user that exists', () => {
        cy.visit('http://freelance-website.test/register');
        cy.get('#name').type('Test User');
        cy.get('#email').type('test2@example.com');
        cy.get('#password').type('password');
        cy.get('#password_confirmation').type('password');
        cy.contains('button', 'Register').click();
        cy.contains('The email has already been taken.');
    });

    const randomEmail = new Date().getTime() + '@example.com';
    it('should register a user and wait for verification', () => {
        cy.visit('http://freelance-website.test/register');
        cy.get('#name').type('Test User');
        cy.get('#email').type(randomEmail);
        cy.get('#password').type('password123456');
        cy.get('#password_confirmation').type('password123456');
        cy.contains('button', 'Register').click();
        cy.contains('verify your email address');
    });
});
