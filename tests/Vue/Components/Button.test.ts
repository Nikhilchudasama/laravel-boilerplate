import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import Button from '@/Components/Form/Button.vue';

describe('Button', () => {
    it('renders slot content', () => {
        const wrapper = mount(Button, {
            slots: {
                default: 'Click Me'
            }
        });
        expect(wrapper.text()).toBe('Click Me');
    });

    it('emits click event', async () => {
        const wrapper = mount(Button);
        await wrapper.trigger('click');
        expect(wrapper.emitted()).toHaveProperty('click');
    });

    it('is disabled when the disabled prop is true', () => {
        const wrapper = mount(Button, {
            props: {
                disabled: true
            }
        });
        const button = wrapper.find('button');
        expect(button.element.disabled).toBe(true);
    });

    it('shows loading state', () => {
        const wrapper = mount(Button, {
            props: {
                loading: true
            }
        });
        // Check for loading spinner or text based on implementation
        expect(wrapper.html()).toContain('svg'); // Assuming it has a spinner
    });
});
