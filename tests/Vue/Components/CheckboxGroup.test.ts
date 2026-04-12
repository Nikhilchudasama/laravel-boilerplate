import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import CheckboxGroup from '@/Components/Form/CheckboxGroup.vue';

describe('CheckboxGroup', () => {
    const defaultOptions = [
        { id: 1, name: 'Option 1' },
        { id: 2, name: 'Option 2' },
        { id: 3, name: 'Option 3' },
    ];

    it('renders the correct number of checkboxes', () => {
        const wrapper = mount(CheckboxGroup, {
            props: {
                modelValue: [],
                options: defaultOptions,
            }
        });

        const inputs = wrapper.findAll('input[type="checkbox"]');
        expect(inputs.length).toBe(3);
    });

    it('binds modelValue correctly', () => {
        const wrapper = mount(CheckboxGroup, {
            props: {
                modelValue: [2],
                options: defaultOptions,
            }
        });

        const checkedInputs = wrapper.findAll('input[type="checkbox"]:checked');
        expect(checkedInputs.length).toBe(1);
        expect((checkedInputs[0].element as HTMLInputElement).value).toBe('2');
    });

    it('emits updated modelValue when a checkbox is toggled', async () => {
        const wrapper = mount(CheckboxGroup, {
            props: {
                modelValue: [1],
                options: defaultOptions,
                'onUpdate:modelValue': (e: any) => wrapper.setProps({ modelValue: e })
            }
        });

        const option2 = wrapper.find('input[value="2"]');
        await option2.setValue(true);

        const emitted = wrapper.emitted('update:modelValue');
        expect(emitted).toBeTruthy();
        expect(emitted![0][0]).toEqual([1, 2]);
    });

    it('displays error message when provided', () => {
        const wrapper = mount(CheckboxGroup, {
            props: {
                modelValue: [],
                options: defaultOptions,
                error: 'Please select at least one option'
            }
        });

        expect(wrapper.text()).toContain('Please select at least one option');
    });

    it('displays label when provided', () => {
        const wrapper = mount(CheckboxGroup, {
            props: {
                modelValue: [],
                options: defaultOptions,
                label: 'Choose your options'
            }
        });

        expect(wrapper.text()).toContain('Choose your options');
    });
});
