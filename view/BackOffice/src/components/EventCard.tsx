import { motion } from 'motion/react';
import { Calendar, MapPin, Users, Clock, HandshakeIcon } from 'lucide-react';
import { Event } from '../types/event';
import { Card } from './ui/card';
import { Badge } from './ui/badge';

interface EventCardProps {
  event: Event;
  delay?: number;
}

const statusColors = {
  upcoming: 'bg-blue-500',
  'in-progress': 'bg-green-500',
  completed: 'bg-gray-500',
  closed: 'bg-red-500'
};

const statusLabels = {
  upcoming: 'Upcoming',
  'in-progress': 'In Progress',
  completed: 'Completed',
  closed: 'Closed'
};

export function EventCard({ event, delay = 0 }: EventCardProps) {
  const daysUntilDeadline = Math.ceil(
    (new Date(event.deadline).getTime() - new Date().getTime()) / (1000 * 60 * 60 * 24)
  );

  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ delay, duration: 0.5 }}
      whileHover={{ y: -5, scale: 1.02 }}
    >
      <Card className="p-5 bg-gradient-to-br from-white to-[#F8F3EB] border-2 border-[#6B85A8]/40 hover:border-[#243B53]/60 transition-all cursor-pointer overflow-hidden relative shadow-lg hover:shadow-2xl">
        {/* Decorative corner star */}
        <div className="absolute top-2 right-2 text-[#6B85A8] opacity-40">
          <motion.div
            animate={{ rotate: 360 }}
            transition={{ duration: 20, repeat: Infinity, ease: "linear" }}
          >
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2L14.09 8.26L20 9.27L15.45 13.14L16.91 19L12 15.77L7.09 19L8.55 13.14L4 9.27L9.91 8.26L12 2Z" />
            </svg>
          </motion.div>
        </div>

        {/* Status Badge */}
        <div className="flex items-center justify-between mb-3">
          <Badge variant="secondary" className={`${statusColors[event.status]} text-white`}>
            {statusLabels[event.status]}
          </Badge>
          <span className="text-xs text-[#4E5F7C]">{event.category}</span>
        </div>

        {/* Title */}
        <h3 className="text-[#243B53] mb-2">{event.title}</h3>
        
        {/* Description */}
        <p className="text-[#4E5F7C] text-sm mb-4 line-clamp-2">{event.description}</p>

        {/* Details */}
        <div className="space-y-2 mb-4">
          <div className="flex items-center gap-2 text-sm text-[#4E5F7C]">
            <Calendar className="h-4 w-4 text-[#6B85A8]" />
            <span>{new Date(event.date).toLocaleDateString('en-US', { 
              month: 'long', 
              day: 'numeric', 
              year: 'numeric' 
            })}</span>
          </div>
          
          <div className="flex items-center gap-2 text-sm text-[#4E5F7C]">
            <MapPin className="h-4 w-4 text-[#6B85A8]" />
            <span>{event.location}</span>
          </div>

          <div className="flex items-center gap-2 text-sm text-[#4E5F7C]">
            <Clock className="h-4 w-4 text-[#6B85A8]" />
            <span>Deadline: {daysUntilDeadline > 0 ? `${daysUntilDeadline} days` : 'Passed'}</span>
          </div>
        </div>

        {/* Stats */}
        <div className="flex items-center justify-between pt-3 border-t border-[#6B85A8]/20">
          <div className="flex items-center gap-2 text-sm text-[#4E5F7C]">
            <Users className="h-4 w-4 text-[#6B85A8]" />
            <span>{event.participantCount} participants</span>
          </div>
          <div className="flex items-center gap-2 text-sm text-[#4E5F7C]">
            <HandshakeIcon className="h-4 w-4 text-[#6B85A8]" />
            <span>{event.sponsorCount} sponsors</span>
          </div>
        </div>
      </Card>
    </motion.div>
  );
}