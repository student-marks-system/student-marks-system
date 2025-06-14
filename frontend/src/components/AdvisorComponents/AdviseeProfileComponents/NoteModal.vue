<template>
  <div v-if="showModal" class="modal-overlay" @click="$emit('close-modal')">
    <div class="modal-content" @click.stop>
      <div class="modal-header">
        <h3>{{ isEditing ? "Edit Note" : "Add New Note" }}</h3>
        <button class="modal-close" @click="$emit('close-modal')">×</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="noteType">Note Type:</label>
          <select
            id="noteType"
            :value="noteForm.type"
            @change="updateNoteType($event.target.value)"
            class="form-select"
          >
            <option value="meeting">Meeting</option>
            <option value="observation">Observation</option>
            <option value="concern">Concern</option>
            <option value="achievement">Achievement</option>
            <option value="plan">Action Plan</option>
          </select>
        </div>
        <div class="form-group">
          <label for="noteContent">Note Content:</label>
          <textarea
            id="noteContent"
            :value="noteForm.content"
            @input="updateNoteContent($event.target.value)"
            class="form-textarea"
            rows="6"
            placeholder="Enter your note here..."
          ></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn secondary" @click="$emit('close-modal')">
          Cancel
        </button>
        <button class="btn primary" @click="$emit('save-note')">
          {{ isEditing ? "Update" : "Save" }} Note
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "NoteModal",
  props: {
    showModal: {
      type: Boolean,
      required: true,
      default: false,
    },
    isEditing: {
      type: Boolean,
      required: true,
      default: false,
    },
    noteForm: {
      type: Object,
      required: true,
      default: () => ({
        type: "meeting",
        content: "",
      }),
    },
  },
  emits: [
    "close-modal",
    "save-note",
    "update-note-type",
    "update-note-content",
  ],
  methods: {
    updateNoteType(type) {
      this.$emit("update-note-type", type);
    },

    updateNoteContent(content) {
      this.$emit("update-note-content", content);
    },
  },
};
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 12px;
  padding: 0;
  width: 90%;
  max-width: 500px;
  max-height: 90vh;
  overflow: hidden;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 24px;
  border-bottom: 1px solid #e2e8f0;
}

.modal-header h3 {
  margin: 0;
  font-weight: 700;
  color: #1e293b;
}

.modal-close {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #64748b;
  padding: 4px;
}

.modal-close:hover {
  color: #1e293b;
}

.modal-body {
  padding: 24px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #1e293b;
}

.form-select,
.form-textarea {
  width: 100%;
  padding: 8px 12px;
  border: 2px solid #e2e8f0;
  border-radius: 6px;
  font-size: 0.9rem;
  font-family: inherit;
}

.form-select:focus,
.form-textarea:focus {
  outline: none;
  border-color: #3b82f6;
}

.form-textarea {
  resize: vertical;
  min-height: 120px;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  padding: 20px 24px;
  border-top: 1px solid #e2e8f0;
  background: #f8fafc;
}

.btn {
  padding: 8px 16px;
  border: none;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.btn.primary {
  background: #3b82f6;
  color: white;
}

.btn.primary:hover {
  background: #2563eb;
}

.btn.secondary {
  background: #f1f5f9;
  color: #475569;
  border: 1px solid #e2e8f0;
}

.btn.secondary:hover {
  background: #e2e8f0;
}

@media (max-width: 768px) {
  .modal-content {
    width: 95%;
    margin: 20px;
  }
}
</style>
