import { motion } from 'motion/react';
import { MoreVertical, MessageSquare, Paperclip, Edit, Trash2 } from 'lucide-react';
import { Task } from '../types/task';
import { Card } from './ui/card';
import { Badge } from './ui/badge';
import { Button } from './ui/button';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from './ui/dropdown-menu';

interface TaskCardProps {
  task: Task;
  onEdit: (task: Task) => void;
  onDelete: (taskId: string) => void;
}

export function TaskCard({ task, onEdit, onDelete }: TaskCardProps) {
  const priorityColors = {
    low: 'bg-blue-100 text-blue-700 border-blue-200',
    medium: 'bg-amber-100 text-amber-700 border-amber-200',
    high: 'bg-rose-100 text-rose-700 border-rose-200',
  };

  const categoryColors = {
    Planning: 'bg-purple-100 text-purple-700',
    Design: 'bg-blue-100 text-blue-700',
    Development: 'bg-green-100 text-green-700',
    Marketing: 'bg-pink-100 text-pink-700',
  };

  // Create progress dots
  const totalDots = 10;
  const filledDots = Math.round((task.progress / 100) * totalDots);

  return (
    <motion.div
      layout
      initial={{ opacity: 0, scale: 0.9 }}
      animate={{ opacity: 1, scale: 1 }}
      exit={{ opacity: 0, scale: 0.9 }}
      whileHover={{ y: -4 }}
      transition={{ duration: 0.2 }}
    >
      <Card className={`p-4 bg-white border-2 ${priorityColors[task.priority]} hover:shadow-lg transition-all cursor-pointer`}>
        {/* Header */}
        <div className="flex items-start justify-between mb-3">
          <div className="flex items-center gap-2 flex-wrap">
            <Badge className={`${categoryColors[task.category as keyof typeof categoryColors] || 'bg-gray-100 text-gray-700'} text-xs border-0`}>
              {task.category}
            </Badge>
            <Badge variant="outline" className="text-xs">
              {task.priority}
            </Badge>
          </div>
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="ghost" size="sm" className="h-6 w-6 p-0">
                <MoreVertical className="h-4 w-4" />
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
              <DropdownMenuItem onClick={() => onEdit(task)}>
                <Edit className="h-4 w-4 mr-2" />
                Edit Task
              </DropdownMenuItem>
              <DropdownMenuItem 
                onClick={() => onDelete(task.id)}
                className="text-red-600"
              >
                <Trash2 className="h-4 w-4 mr-2" />
                Delete Task
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>

        {/* Title */}
        <h4 className="text-[#243B53] mb-2 text-sm line-clamp-2">
          {task.title}
        </h4>

        {/* Description */}
        <p className="text-xs text-gray-500 mb-3 line-clamp-2">
          {task.description}
        </p>

        {/* Progress Dots */}
        <div className="flex items-center gap-1 mb-3">
          {Array.from({ length: totalDots }).map((_, index) => (
            <div
              key={index}
              className={`w-2 h-2 rounded-full transition-all ${
                index < filledDots
                  ? task.priority === 'high'
                    ? 'bg-rose-500'
                    : task.priority === 'medium'
                    ? 'bg-amber-500'
                    : 'bg-blue-500'
                  : 'bg-gray-200'
              }`}
            />
          ))}
          <span className="text-xs text-gray-500 ml-2">{task.progress}%</span>
        </div>

        {/* Footer */}
        <div className="flex items-center justify-between">
          {/* Assignees */}
          <div className="flex -space-x-2">
            {task.assignees.map((assignee, index) => (
              <div
                key={index}
                className="w-6 h-6 rounded-full bg-gradient-to-br from-[#243B53] to-[#4E5F7C] text-white text-xs flex items-center justify-center border-2 border-white"
              >
                {assignee}
              </div>
            ))}
          </div>

          {/* Stats */}
          <div className="flex items-center gap-3 text-xs text-gray-500">
            {task.comments > 0 && (
              <div className="flex items-center gap-1">
                <MessageSquare className="h-3 w-3" />
                {task.comments}
              </div>
            )}
            {task.attachments > 0 && (
              <div className="flex items-center gap-1">
                <Paperclip className="h-3 w-3" />
                {task.attachments}
              </div>
            )}
          </div>
        </div>
      </Card>
    </motion.div>
  );
}
