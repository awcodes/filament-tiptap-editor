import BulletList from "@tiptap/extension-bullet-list";

const CheckList = BulletList.extend({
    name: "CheckList",
    addAttributes() {
        return {
            class: {
                default: "check-list",
            },
        };
    },
});

export default CheckList;
