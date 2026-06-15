import { describe, it, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import Button from '@/Components/Form/Button.vue';

describe('ConfirmModal', () => {
    // A helper to mount because custom sub-components might need stubbing
    // but in this setup, test-utils easily mounts them by default.

    it('renders title and message when show is true', () => {
        const wrapper = mount(ConfirmModal, {
            props: {
                show: true,
                title: 'Delete Item',
                message: 'Are you sure you want to delete this item?',
                type: 'danger'
            },
            global: {
                stubs: {
                    teleport: true
                }
            }
        });

        expect(wrapper.text()).toContain('Delete Item');
        expect(wrapper.text()).toContain('Are you sure you want to delete this item?');
    });

    it('emits confirm when primary button is clicked', async () => {
        const wrapper = mount(ConfirmModal, {
            props: {
                show: true,
                title: 'Delete Item',
                message: 'Are you sure?'
            },
            global: {
                stubs: {
                    teleport: true
                }
            }
        });

        // The first button in the actions is the Confirm button
        const buttons = wrapper.findAllComponents(Button);
        await buttons[0].trigger('click');

        expect(wrapper.emitted('confirm')).toBeTruthy();
    });

    it('emits close when cancel button is clicked', async () => {
        const wrapper = mount(ConfirmModal, {
            props: {
                show: true,
                title: 'Delete Item',
                message: 'Are you sure?'
            },
            global: {
                stubs: {
                    teleport: true
                }
            }
        });

        // The second button is the Cancel button
        const buttons = wrapper.findAllComponents(Button);
        expect(buttons.length).toBeGreaterThanOrEqual(2);

        await buttons[1].trigger('click');

        expect(wrapper.emitted('close')).toBeTruthy();
    });
});
