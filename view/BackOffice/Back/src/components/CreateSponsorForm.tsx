import { useState } from 'react';
import { motion } from 'motion/react';
import { X, HandshakeIcon, Mail, Phone, Building, DollarSign } from 'lucide-react';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from './ui/dialog';
import { Button } from './ui/button';
import { Input } from './ui/input';
import { Label } from './ui/label';
import { Textarea } from './ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from './ui/select';

interface CreateSponsorFormProps {
  open: boolean;
  onClose: () => void;
}

export function CreateSponsorForm({ open, onClose }: CreateSponsorFormProps) {
  const [formData, setFormData] = useState({
    name: '',
    type: '',
    eventName: '',
    contactEmail: '',
    contactPhone: '',
    contributionAmount: '',
    contributionNotes: '',
    contractStatus: 'pending',
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    console.log('Sponsor created:', formData);
    // Here you would handle the actual sponsor creation
    onClose();
  };

  return (
    <Dialog open={open} onOpenChange={onClose}>
      <DialogContent className="max-w-2xl bg-white border-2 border-[#6B85A8]/40">
        <DialogHeader>
          <DialogTitle className="text-2xl text-[#243B53] flex items-center gap-2">
            <HandshakeIcon className="h-6 w-6" />
            Add New Sponsor
          </DialogTitle>
        </DialogHeader>

        <form onSubmit={handleSubmit} className="space-y-4 mt-4">
          {/* Sponsor Name */}
          <div className="space-y-2">
            <Label htmlFor="name" className="text-[#243B53]">
              <Building className="h-4 w-4 inline mr-1" />
              Sponsor Name *
            </Label>
            <Input
              id="name"
              value={formData.name}
              onChange={(e) => setFormData({ ...formData, name: e.target.value })}
              placeholder="e.g., Tech Corp International"
              required
              className="border-[#6B85A8]/30 focus:border-[#243B53]"
            />
          </div>

          {/* Type and Event */}
          <div className="grid grid-cols-2 gap-4">
            <div className="space-y-2">
              <Label htmlFor="type" className="text-[#243B53]">Sponsorship Type *</Label>
              <Select
                value={formData.type}
                onValueChange={(value) => setFormData({ ...formData, type: value })}
              >
                <SelectTrigger className="border-[#6B85A8]/30">
                  <SelectValue placeholder="Select type" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="financial">Financial</SelectItem>
                  <SelectItem value="media">Media</SelectItem>
                  <SelectItem value="equipment">Equipment</SelectItem>
                  <SelectItem value="other">Other</SelectItem>
                </SelectContent>
              </Select>
            </div>

            <div className="space-y-2">
              <Label htmlFor="eventName" className="text-[#243B53]">Event Name *</Label>
              <Input
                id="eventName"
                value={formData.eventName}
                onChange={(e) => setFormData({ ...formData, eventName: e.target.value })}
                placeholder="e.g., Annual Tech Conference"
                required
                className="border-[#6B85A8]/30 focus:border-[#243B53]"
              />
            </div>
          </div>

          {/* Contact Information */}
          <div className="grid grid-cols-2 gap-4">
            <div className="space-y-2">
              <Label htmlFor="contactEmail" className="text-[#243B53]">
                <Mail className="h-4 w-4 inline mr-1" />
                Contact Email *
              </Label>
              <Input
                id="contactEmail"
                type="email"
                value={formData.contactEmail}
                onChange={(e) => setFormData({ ...formData, contactEmail: e.target.value })}
                placeholder="contact@sponsor.com"
                required
                className="border-[#6B85A8]/30 focus:border-[#243B53]"
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="contactPhone" className="text-[#243B53]">
                <Phone className="h-4 w-4 inline mr-1" />
                Contact Phone *
              </Label>
              <Input
                id="contactPhone"
                type="tel"
                value={formData.contactPhone}
                onChange={(e) => setFormData({ ...formData, contactPhone: e.target.value })}
                placeholder="+216 XX XXX XXX"
                required
                className="border-[#6B85A8]/30 focus:border-[#243B53]"
              />
            </div>
          </div>

          {/* Contribution Amount */}
          <div className="space-y-2">
            <Label htmlFor="contributionAmount" className="text-[#243B53]">
              <DollarSign className="h-4 w-4 inline mr-1" />
              Contribution Amount (Optional)
            </Label>
            <Input
              id="contributionAmount"
              type="text"
              value={formData.contributionAmount}
              onChange={(e) => setFormData({ ...formData, contributionAmount: e.target.value })}
              placeholder="e.g., $5,000 or Equipment worth $2,000"
              className="border-[#6B85A8]/30 focus:border-[#243B53]"
            />
          </div>

          {/* Contribution Notes */}
          <div className="space-y-2">
            <Label htmlFor="contributionNotes" className="text-[#243B53]">Contribution Details</Label>
            <Textarea
              id="contributionNotes"
              value={formData.contributionNotes}
              onChange={(e) => setFormData({ ...formData, contributionNotes: e.target.value })}
              placeholder="Describe what the sponsor is contributing..."
              rows={3}
              className="border-[#6B85A8]/30 focus:border-[#243B53]"
            />
          </div>

          {/* Contract Status */}
          <div className="space-y-2">
            <Label htmlFor="contractStatus" className="text-[#243B53]">Contract Status</Label>
            <Select
              value={formData.contractStatus}
              onValueChange={(value) => setFormData({ ...formData, contractStatus: value })}
            >
              <SelectTrigger className="border-[#6B85A8]/30">
                <SelectValue />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="pending">Pending</SelectItem>
                <SelectItem value="signed">Signed</SelectItem>
                <SelectItem value="in-negotiation">In Negotiation</SelectItem>
                <SelectItem value="completed">Completed</SelectItem>
              </SelectContent>
            </Select>
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
              Add Sponsor
            </Button>
          </div>
        </form>
      </DialogContent>
    </Dialog>
  );
}
