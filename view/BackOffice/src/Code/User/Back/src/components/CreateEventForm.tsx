import { useState } from 'react';
import { motion } from 'motion/react';
import { X, Calendar, MapPin, Users, Clock } from 'lucide-react';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from './ui/dialog';
import { Button } from './ui/button';
import { Input } from './ui/input';
import { Label } from './ui/label';
import { Textarea } from './ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from './ui/select';

interface CreateEventFormProps {
  open: boolean;
  onClose: () => void;
}

export function CreateEventForm({ open, onClose }: CreateEventFormProps) {
  const [formData, setFormData] = useState({
    title: '',
    description: '',
    category: '',
    date: '',
    deadline: '',
    location: '',
    maxParticipants: '',
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    console.log('Event created:', formData);
    // Here you would handle the actual event creation
    onClose();
  };

  return (
    <Dialog open={open} onOpenChange={onClose}>
      <DialogContent className="max-w-2xl bg-white border-2 border-[#6B85A8]/40">
        <DialogHeader>
          <DialogTitle className="text-2xl text-[#243B53] flex items-center gap-2">
            <Calendar className="h-6 w-6" />
            Create New Event
          </DialogTitle>
        </DialogHeader>

        <form onSubmit={handleSubmit} className="space-y-4 mt-4">
          {/* Event Title */}
          <div className="space-y-2">
            <Label htmlFor="title" className="text-[#243B53]">Event Title *</Label>
            <Input
              id="title"
              value={formData.title}
              onChange={(e) => setFormData({ ...formData, title: e.target.value })}
              placeholder="e.g., Annual Tech Conference 2025"
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
              placeholder="Provide a detailed description of the event..."
              required
              rows={4}
              className="border-[#6B85A8]/30 focus:border-[#243B53]"
            />
          </div>

          {/* Category and Location */}
          <div className="grid grid-cols-2 gap-4">
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
                  <SelectItem value="conference">Conference</SelectItem>
                  <SelectItem value="workshop">Workshop</SelectItem>
                  <SelectItem value="seminar">Seminar</SelectItem>
                  <SelectItem value="networking">Networking</SelectItem>
                  <SelectItem value="competition">Competition</SelectItem>
                  <SelectItem value="other">Other</SelectItem>
                </SelectContent>
              </Select>
            </div>

            <div className="space-y-2">
              <Label htmlFor="location" className="text-[#243B53]">
                <MapPin className="h-4 w-4 inline mr-1" />
                Location *
              </Label>
              <Input
                id="location"
                value={formData.location}
                onChange={(e) => setFormData({ ...formData, location: e.target.value })}
                placeholder="e.g., Tunis, Tunisia"
                required
                className="border-[#6B85A8]/30 focus:border-[#243B53]"
              />
            </div>
          </div>

          {/* Date and Deadline */}
          <div className="grid grid-cols-2 gap-4">
            <div className="space-y-2">
              <Label htmlFor="date" className="text-[#243B53]">
                <Calendar className="h-4 w-4 inline mr-1" />
                Event Date *
              </Label>
              <Input
                id="date"
                type="date"
                value={formData.date}
                onChange={(e) => setFormData({ ...formData, date: e.target.value })}
                required
                className="border-[#6B85A8]/30 focus:border-[#243B53]"
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="deadline" className="text-[#243B53]">
                <Clock className="h-4 w-4 inline mr-1" />
                Registration Deadline *
              </Label>
              <Input
                id="deadline"
                type="date"
                value={formData.deadline}
                onChange={(e) => setFormData({ ...formData, deadline: e.target.value })}
                required
                className="border-[#6B85A8]/30 focus:border-[#243B53]"
              />
            </div>
          </div>

          {/* Max Participants */}
          <div className="space-y-2">
            <Label htmlFor="maxParticipants" className="text-[#243B53]">
              <Users className="h-4 w-4 inline mr-1" />
              Maximum Participants
            </Label>
            <Input
              id="maxParticipants"
              type="number"
              value={formData.maxParticipants}
              onChange={(e) => setFormData({ ...formData, maxParticipants: e.target.value })}
              placeholder="e.g., 100"
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
              Create Event
            </Button>
          </div>
        </form>
      </DialogContent>
    </Dialog>
  );
}
