import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import TextInput from '@/Components/Form/TextInput.vue';

describe('TextInput', () => {
    it('updates modelValue on input', async () => {
        const wrapper = mount(TextInput, {
            props: {
                modelValue: '',
                'onUpdate:modelValue': (e: any) => wrapper.setProps({ modelValue: e })
            }
        });

        const input = wrapper.find('input');
        await input.setValue('hello world');

        expect(wrapper.emitted()['update:modelValue'][0]).toEqual(['hello world']);
    });

    it('displays error message when provided', () => {
        const wrapper = mount(TextInput, {
            props: {
                modelValue: '',
                error: 'Field is required'
            }
        });
        expect(wrapper.text()).toContain('Field is required');
    });

    it('has the correct input type', () => {
        const wrapper = mount(TextInput, {
            props: {
                modelValue: '',
                type: 'password'
            }
        });
        expect(wrapper.find('input').attributes('type')).toBe('password');
    });
});
