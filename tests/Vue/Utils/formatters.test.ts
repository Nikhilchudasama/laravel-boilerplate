import { describe, it, expect } from 'vitest';
import { formatDate, formatRelativeTime, formatCurrency, formatNumber, truncate } from '@/Utils/formatters';

describe('formatters', () => {
    describe('formatDate', () => {
        it('formats date in short format', () => {
            const date = new Date('2024-01-15');
            const formatted = formatDate(date, 'short');
            expect(formatted).toContain('1/15/2024');
        });

        it('formats date in long format', () => {
            const date = new Date('2024-01-15');
            const formatted = formatDate(date, 'long');
            expect(formatted).toContain('January');
            expect(formatted).toContain('15');
            expect(formatted).toContain('2024');
        });
    });

    describe('formatCurrency', () => {
        it('formats USD currency', () => {
            const formatted = formatCurrency(1234.56);
            expect(formatted).toBe('$1,234.56');
        });

        it('formats EUR currency', () => {
            const formatted = formatCurrency(1234.56, 'EUR');
            expect(formatted).toContain('1,234.56');
        });
    });

    describe('formatNumber', () => {
        it('formats number with thousand separators', () => {
            const formatted = formatNumber(1234567);
            expect(formatted).toBe('1,234,567');
        });
    });

    describe('truncate', () => {
        it('truncates long text', () => {
            const text = 'This is a very long text that should be truncated';
            const truncated = truncate(text, 20);
            expect(truncated).toBe('This is a very long ...');
        });

        it('does not truncate short text', () => {
            const text = 'Short text';
            const truncated = truncate(text, 20);
            expect(truncated).toBe('Short text');
        });

        it('uses custom suffix', () => {
            const text = 'This is a very long text';
            const truncated = truncate(text, 10, '---');
            expect(truncated).toBe('This is a ---');
        });
    });
});
