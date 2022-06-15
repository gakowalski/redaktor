import {EditorView, basicSetup} from "codemirror"
import {keymap} from "@codemirror/view"
import {javascript} from "@codemirror/lang-javascript"
import {indentWithTab} from "@codemirror/commands"

let editor = new EditorView({
  extensions: [
    basicSetup,
    keymap.of([indentWithTab]),
    javascript()
  ],
  parent: document.getElementById('editor')
})

editor.dispatch({
  changes: { from: 0, insert: document.getElementById('content').textContent }
});

document.getElementById('submit').addEventListener('click', (e) => {
  document.getElementById('content').textContent = editor.state.doc.toString();
})
