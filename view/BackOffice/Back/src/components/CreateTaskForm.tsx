import { useState } from 'react';
import { motion } from 'motion/react';
import { X, CheckSquare, Calendar as CalendarIcon } from 'lucide-react';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from './ui/dialog';
import { Button } from './ui/button';
import { Input } from './ui/input';
import { Label } from './ui/label';
import { Textarea } from './ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from './ui/select';
import { Task } from '../types/task';

interface CreateTaskFormProps {
  open: boolean;
  onClose: () => void;
  onSubmit: (task: Partial<Task>) => void;
  editTask?: Task | null;
}

export function CreateTaskForm({ open, onClose, onSubmit, editTask }: CreateTaskFormProps) {
  const [formData, setFormData] = useState<Partial<Task>>(
    editTask || {
      title: '',
      description: '',
      status: 'todo',
      priority: 'medium',
      category: '',
      progress: 0,
      comments: 0,
      attachments: 0,
      assignees: [],
      tags: [],
    }
  );

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSubmit(formData);
    onClose();
  };

  const handleAssigneesChange = (value: string) => {
    const assignees = value.split(',').map(a => a.trim()).filter(a => a);
    setFormData({ ...formData, assignees });
  };

  const handleTagsChange = (value: string) => {
    const tags = value.split(',').map(t => t.trim()).filter(t => t);
    setFormData({ ...formData, tags });
  };

  return (
    <Dialog open={open} onOpenChange={onClose}>
      <DialogContent className="max-w-2xl bg-white border-2 border-[#6B85A8]/40 max-h-[90vh] overflow-y-auto">
        <DialogHeader>
          <DialogTitle className="text-2xl text-[#243B53] flex items-center gap-2">
            <CheckSquare className="h-6 w-6" />
            {editTask ? 'Edit Task' : 'Create New Task'}
          </DialogTitle>
        </DialogHeader>

        <form onSubmit={handleSubmit} className="space-y-4 mt-4">
          {/* Task Title */}
          <div className="space-y-2">
            <Label htmlFor="title" className="text-[#243B53]">Task Title *</Label>
            <Input
              id="title"
              value={formData.title}
              onChange={(e) => setFormData({ ...formData, title: e.target.value })}
              placeholder="e.g., Design event landing page"
              required
              className="border-[#6B85A8]/30 focus:border-[#243B53]"
            />
          </div>

          {/* Description */}
          <div className="space-y-2">
            <Label htmlFor="description" className="text-[#243B53]">Description *</Label>
            <Textarea
              id="description"
              value={formData.description}
              onChange={(e) => setFormData({ ...formData, description: e.target.value })}
              placeholder="Provide details about the task..."
              required
              rows={3}
              className="border-[#6B85A8]/30 focus:border-[#243B53]"
            />
          </div>

          {/* Status, Priority, and Category */}
          <div className="grid grid-cols-3 gap-4">
            <div className="space-y-2">
              <Label htmlFor="status" className="text-[#243B53]">Status *</Label>
              <Select
                value={formData.status}
                onValueChange={(value: Task['status']) => setFormData({ ...formData, status: value })}
              >
                <SelectTrigger className="border-[#6B85A8]/30">
                  <SelectValue placeholder="Select status" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="todo">To Do</SelectItem>
                  <SelectItem value="in-progress">In Progress</SelectItem>
                  <SelectItem value="in-review">In Review</SelectItem>
                  <SelectItem value="done">Done</SelectItem>
                </SelectContent>
              </Select>
            </div>

            <div className="space-y-2">
              <Label htmlFor="priority" className="text-[#243B53]">Priority *</Label>
              <Select
                value={formData.priority}
                onValueChange={(value: Task['priority']) => setFormData({ ...formData, priority: value })}
              >
                <SelectTrigger className="border-[#6B85A8]/30">
                  <SelectValue placeholder="Select priority" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="low">Low</SelectItem>
                  <SelectItem value="medium">Medium</SelectItem>
                  <SelectItem value="high">High</SelectItem>
                </SelectContent>
              </Select>
            </div>

            <div className="space-y-2">
              <Label htmlFor="category" className="text-[#243B53]">Category *</Label>
              <Select
                value={formData.category}
                onValueChange={(value) => setFormData({ ...formData, category: value })}
              >
                <SelectTrigger className="border-[#6B85A8]/30">
                  <SelectValue placeholder="Select category" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="Planning">Planning</SelectItem>
                  <SelectItem value="Design">Design</SelectItem>
                  <SelectItem value="Development">Development</SelectItem>
                  <SelectItem value="Marketing">Marketing</SelectItem>
                  <SelectItem value="Operations">Operations</SelectItem>
                  <SelectItem value="Other">Other</SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>

          {/* Progress */}
          <div className="space-y-2">
            <Label htmlFor="progress" className="text-[#243B53]">
              Progress: {formData.progress}%
            </Label>
            <input
              id="progress"
              type="range"
              min="0"
              max="100"
              step="5"
              value={formData.progress}
              onChange={(e) => setFormData({ ...formData, progress: parseInt(e.target.value) })}
              className="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-[#243B53]"
            />
          </div>

          {/* Assignees */}
          <div className="space-y-2">
            <Label htmlFor="assignees" className="text-[#243B53]">
              Assignees (initials separated by commas)
            </Label>
            <Input
              id="assignees"
              value={formData.assignees?.join(', ') || ''}
              onChange={(e) => handleAssigneesChange(e.target.value)}
              placeholder="e.g., JS, AD, MK"
              className="border-[#6B85A8]/30 focus:border-[#243B53]"
            />
            <p className="text-xs text-gray-500">Enter initials like: JS, AD, MK</p>
          </div>

          {/* Tags */}
          <div className="space-y-2">
            <Label htmlFor="tags" className="text-[#243B53]">
              Tags (separated by commas)
            </Label>
            <Input
              id="tags"
              value={formData.tags?.join(', ') || ''}
              onChange={(e) => handleTagsChange(e.target.value)}
              placeholder="e.g., design, urgent, event"
              className="border-[#6B85A8]/30 focus:border-[#243B53]"
            />
          </div>

          {/* Due Date */}
          <div className="space-y-2">
            <Label htmlFor="dueDate" className="text-[#243B53]">
              <CalendarIcon className="h-4 w-4 inline mr-1" />
              Due Date
            </Label>
            <Input
              id="dueDate"
              type="date"
              value={formData.dueDate || ''}
              onChange={(e) => setFormData({ ...formData, dueDate: e.target.value })}
              className="border-[#6B85A8]/30 focus:border-[#243B53]"
            />
          </div>

          {/* Action Buttons */}
          <div className="flex gap-3 pt-4">
            <Button
              type="button"
              variant="outline"
              onClick={onClose}
              className="flex-1"
            >
              Cancel
            </Button>
            <Button
              type="submit"
              className="flex-1 bg-gradient-to-r from-[#243B53] to-[#4E5F7C] text-white hover:from-[#4E5F7C] hover:to-[#243B53]"
            >
              {editTask ? 'Update Task' : 'Create Task'}
            </Button>
          </div>
        </form>
      </DialogContent>
    </Dialog>
  );
}
