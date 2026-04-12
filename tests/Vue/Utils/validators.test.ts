import { describe, it, expect } from 'vitest';
import {
    isValidEmail,
    isStrongPassword,
    isValidUrl,
    isRequired,
    minLength,
    maxLength,
    isNumeric,
    isValidPhone,
} from '@/Utils/validators';

describe('validators', () => {
    describe('isValidEmail', () => {
        it('validates correct email', () => {
            expect(isValidEmail('test@example.com')).toBe(true);
        });

        it('rejects invalid email', () => {
            expect(isValidEmail('invalid-email')).toBe(false);
            expect(isValidEmail('test@')).toBe(false);
            expect(isValidEmail('@example.com')).toBe(false);
        });
    });

    describe('isStrongPassword', () => {
        it('validates strong password', () => {
            expect(isStrongPassword('Password123')).toBe(true);
        });

        it('rejects weak password', () => {
            expect(isStrongPassword('password')).toBe(false); // no uppercase, no number
            expect(isStrongPassword('PASSWORD')).toBe(false); // no lowercase, no number
            expect(isStrongPassword('Pass1')).toBe(false); // too short
        });
    });

    describe('isValidUrl', () => {
        it('validates correct URL', () => {
            expect(isValidUrl('https://example.com')).toBe(true);
            expect(isValidUrl('http://example.com')).toBe(true);
        });

        it('rejects invalid URL', () => {
            expect(isValidUrl('not-a-url')).toBe(false);
            expect(isValidUrl('example.com')).toBe(false);
        });
    });

    describe('isRequired', () => {
        it('validates non-empty value', () => {
            expect(isRequired('test')).toBe(true);
            expect(isRequired(123)).toBe(true);
        });

        it('rejects empty value', () => {
            expect(isRequired('')).toBe(false);
            expect(isRequired('   ')).toBe(false);
            expect(isRequired(null)).toBe(false);
            expect(isRequired(undefined)).toBe(false);
        });
    });

    describe('minLength', () => {
        it('validates minimum length', () => {
            expect(minLength('test', 3)).toBe(true);
            expect(minLength('test', 4)).toBe(true);
        });

        it('rejects too short', () => {
            expect(minLength('test', 5)).toBe(false);
        });
    });

    describe('maxLength', () => {
        it('validates maximum length', () => {
            expect(maxLength('test', 5)).toBe(true);
            expect(maxLength('test', 4)).toBe(true);
        });

        it('rejects too long', () => {
            expect(maxLength('test', 3)).toBe(false);
        });
    });

    describe('isNumeric', () => {
        it('validates numeric string', () => {
            expect(isNumeric('123')).toBe(true);
            expect(isNumeric('123.45')).toBe(true);
        });

        it('rejects non-numeric string', () => {
            expect(isNumeric('abc')).toBe(false);
            expect(isNumeric('12a')).toBe(false);
        });
    });

    describe('isValidPhone', () => {
        it('validates phone number', () => {
            expect(isValidPhone('1234567890')).toBe(true);
            expect(isValidPhone('(123) 456-7890')).toBe(true);
            expect(isValidPhone('+1 123-456-7890')).toBe(true);
        });

        it('rejects invalid phone', () => {
            expect(isValidPhone('123')).toBe(false);
            expect(isValidPhone('abc')).toBe(false);
        });
    });
});
