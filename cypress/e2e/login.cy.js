describe('Login', () => {
    it('should not login a user', () => {
        cy.visit('http://freelance-website.test/login');
        cy.get('input[type="email"]').type('doesnotexist@user.com');
        cy.get('input[type="password"]').type('password');
        cy.contains('button', 'Log in').click();
        cy.contains('These credentials do not match our records.');
    });

    it('should login a user', () => {
        cy.visit('http://freelance-website.test/login');
        cy.get('input[type="email"]').type('test@example.com');
        cy.get('input[type="password"]').type('password');
        cy.contains('button', 'Log in').click();
        cy.contains('Dashboard');
    });
});
