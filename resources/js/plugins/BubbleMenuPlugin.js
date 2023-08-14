import {Plugin, PluginKey} from '@tiptap/pm/state'
import tippy from 'tippy.js'
import {
    Editor, isNodeSelection, isTextSelection, posToDOMRect,
} from '@tiptap/core'


export class BubbleMenuView {

    shouldShow = ({view, state, from, to}) => {
        const {doc, selection} = state
        const {empty} = selection

        // Sometime check for `empty` is not enough.
        // Doubleclick an empty paragraph returns a node size of 2.
        // So we check also for an empty text size.
        const isEmptyTextBlock = !doc.textBetween(from, to).length && isTextSelection(state.selection)

        // When clicking on a element inside the bubble menu the editor "blur" event
        // is called and the bubble menu item is focussed. In this case we should
        // consider the menu as part of the editor and keep showing the menu
        const isChildOfMenu = this.element.contains(document.activeElement)

        const hasEditorFocus = view.hasFocus() || isChildOfMenu

        return !(!hasEditorFocus || empty || isEmptyTextBlock || !this.editor.isEditable);
    }

    constructor({editor, element, view, tippyOptions = {}, updateDelay = 250, shouldShow}) {
        this.editor = editor
        this.element = element
        this.view = view
        this.updateDelay = updateDelay

        if (shouldShow) {
            this.shouldShow = shouldShow
        }

        this.element.addEventListener('mousedown', this.mousedownHandler, {capture: true})
        this.view.dom.addEventListener('dragstart', this.dragstartHandler)
        this.editor.on('focus', this.focusHandler)
        this.editor.on('blur', this.blurHandler)
        this.tippyOptions = tippyOptions
        // Detaches menu content from its current parent
        // this.element.remove()
        this.element.style.visibility = 'hidden'
        this.element.style.position = 'absolute'
    }

    mousedownHandler = () => {
        this.preventHide = true
    }

    dragstartHandler = () => {
        this.hide()
    }

    focusHandler = () => {
        // we use `setTimeout` to make sure `selection` is already updated
        setTimeout(() => this.update(this.editor.view))
    }

    blurHandler = ({event}) => {
        if (this.preventHide) {
            this.preventHide = false

            return
        }

        if (event?.relatedTarget && this.element.parentNode?.contains(event.relatedTarget)) {
            return
        }

        this.hide()
    }

    tippyBlurHandler = (event) => {
        this.blurHandler({event})
    }

    createTooltip() {
        const {element: editorElement} = this.editor.options
        const editorIsAttached = !!editorElement.parentElement

        if (this.tippy || !editorIsAttached) {
            return
        }

        this.tippy = tippy(editorElement, {
            duration: 0,
            getReferenceClientRect: null,
            content: this.element,
            interactive: true,
            trigger: 'manual',
            placement: 'top',
            hideOnClick: 'toggle',
            ...this.tippyOptions,
        })

        // maybe we have to hide tippy on its own blur event as well
        if (this.tippy.popper.firstChild) {
            (this.tippy.popper.firstChild).addEventListener('blur', this.tippyBlurHandler)
        }
    }

    update(view, oldState) {
        const {state} = view
        const hasValidSelection = state.selection.$from.pos !== state.selection.$to.pos

        if (this.updateDelay > 0 && hasValidSelection) {
            this.handleDebouncedUpdate(view, oldState)
            return
        }

        const selectionChanged = !oldState?.selection.eq(view.state.selection)
        const docChanged = !oldState?.doc.eq(view.state.doc)

        this.updateHandler(view, selectionChanged, docChanged, oldState)
    }

    handleDebouncedUpdate = (view, oldState) => {
        const selectionChanged = !oldState?.selection.eq(view.state.selection)
        const docChanged = !oldState?.doc.eq(view.state.doc)

        if (!selectionChanged && !docChanged) {
            return
        }

        if (this.updateDebounceTimer) {
            clearTimeout(this.updateDebounceTimer)
        }

        this.updateDebounceTimer = window.setTimeout(() => {
            this.updateHandler(view, selectionChanged, docChanged, oldState)
        }, this.updateDelay)
    }

    updateHandler = (view, selectionChanged, docChanged, oldState) => {
        const {state, composing} = view
        const {selection} = state

        const isSame = !selectionChanged && !docChanged

        if (composing || isSame) {
            return
        }

        this.createTooltip()

        // support for CellSelections
        const {ranges} = selection
        const from = Math.min(...ranges.map(range => range.$from.pos))
        const to = Math.max(...ranges.map(range => range.$to.pos))

        const shouldShow = this.shouldShow?.({
            editor: this.editor,
            view,
            state,
            oldState,
            from,
            to,
        })

        if (!shouldShow) {
            this.hide()

            return
        }

        if (state.selection.$anchor.pos !== oldState?.selection.$anchor.pos) {
            this.update(view, state);
        }

        this.tippy?.setProps({
            getReferenceClientRect:
                this.tippyOptions?.getReferenceClientRect
                || (() => {
                    if (isNodeSelection(state.selection)) {
                        let node = view.nodeDOM(from)

                        const nodeViewWrapper = node.dataset.nodeViewWrapper ? node : node.querySelector('[data-node-view-wrapper]')

                        if (nodeViewWrapper) {
                            node = nodeViewWrapper.firstChild
                        }

                        if (node) {
                            return node.getBoundingClientRect()
                        }
                    }

                    return posToDOMRect(view, from, to)
                }),
        })

        this.show()
    }

    show() {
        this.element.style.position = 'relative'
        this.element.style.visibility = 'visible'
        this.tippy?.show()
    }

    hide() {
        this.element.style.position = 'absolute'
        this.element.style.visibility = 'hidden'
        this.tippy?.hide()
    }

    destroy() {
        if (this.tippy?.popper.firstChild) {
            (this.tippy.popper.firstChild).removeEventListener(
                'blur',
                this.tippyBlurHandler,
            )
        }
        this.tippy?.destroy()
        this.element.removeEventListener('mousedown', this.mousedownHandler, {capture: true})
        this.view.dom.removeEventListener('dragstart', this.dragstartHandler)
        this.editor.off('focus', this.focusHandler)
        this.editor.off('blur', this.blurHandler)
    }
}

export const BubbleMenuPlugin = (options) => {
    return new Plugin({
        key: typeof options.pluginKey === 'string' ? new PluginKey(options.pluginKey) : options.pluginKey,
        view: view => new BubbleMenuView({view, ...options}),
    })
}
