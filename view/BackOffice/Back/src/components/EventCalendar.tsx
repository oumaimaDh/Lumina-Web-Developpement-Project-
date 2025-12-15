import { useState } from 'react';
import { motion } from 'motion/react';
import { Calendar } from './ui/calendar';
import { Card } from './ui/card';
import { Badge } from './ui/badge';
import { Event } from '../types/event';
import { Calendar as CalendarIcon, ChevronLeft, ChevronRight } from 'lucide-react';
import { Button } from './ui/button';

interface EventCalendarProps {
  events: Event[];
}

export function EventCalendar({ events }: EventCalendarProps) {
  const [selectedDate, setSelectedDate] = useState<Date | undefined>(new Date());

  // Get events for selected date
  const eventsOnSelectedDate = events.filter(event => {
    if (!selectedDate) return false;
    const eventDate = new Date(event.date);
    return eventDate.toDateString() === selectedDate.toDateString();
  });

  // Get all event dates for highlighting
  const eventDates = events.map(event => new Date(event.date));

  return (
    <motion.div
      initial={{ opacity: 0, scale: 0.95 }}
      animate={{ opacity: 1, scale: 1 }}
      transition={{ duration: 0.5 }}
    >
      <Card className="p-5 bg-white border-2 border-[#6B85A8]/40 shadow-lg">
        <div className="flex items-center gap-2 mb-4">
          <CalendarIcon className="h-5 w-5 text-[#243B53]" />
          <h4 className="text-[#243B53]">Event Calendar</h4>
        </div>

        <Calendar
          mode="single"
          selected={selectedDate}
          onSelect={setSelectedDate}
          className="rounded-md border border-[#6B85A8]/20"
          modifiers={{
            eventDay: eventDates,
          }}
          modifiersStyles={{
            eventDay: {
              fontWeight: 'bold',
              backgroundColor: '#6B85A8',
              color: 'white',
              borderRadius: '50%',
            },
          }}
        />

        {/* Events on selected date */}
        {selectedDate && (
          <div className="mt-4 space-y-2">
            <h5 className="text-[#243B53] text-sm">
              Events on {selectedDate.toLocaleDateString('en-US', { 
                month: 'long', 
                day: 'numeric',
                year: 'numeric'
              })}
            </h5>
            {eventsOnSelectedDate.length > 0 ? (
              <div className="space-y-2">
                {eventsOnSelectedDate.map((event) => (
                  <motion.div
                    key={event.id}
                    className="p-3 bg-gradient-to-r from-[#F4EDE4] to-[#E8D5D9] rounded-lg"
                    initial={{ opacity: 0, x: -20 }}
                    animate={{ opacity: 1, x: 0 }}
                    whileHover={{ x: 5 }}
                  >
                    <p className="text-[#243B53] text-sm">{event.title}</p>
                    <p className="text-[#4E5F7C] text-xs">{event.location}</p>
                    <Badge className="mt-1 text-xs bg-[#243B53] text-white">
                      {event.status}
                    </Badge>
                  </motion.div>
                ))}
              </div>
            ) : (
              <p className="text-[#4E5F7C] text-sm">No events scheduled</p>
            )}
          </div>
        )}

        {/* Legend */}
        <div className="mt-4 pt-4 border-t border-[#6B85A8]/20">
          <div className="flex items-center gap-2 text-xs text-[#4E5F7C]">
            <div className="w-3 h-3 bg-[#6B85A8] rounded-full" />
            <span>Days with events</span>
          </div>
        </div>
      </Card>
    </motion.div>
  );
}
