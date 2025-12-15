import { useState } from 'react';
import { motion, AnimatePresence } from 'motion/react';
import { Plus, Filter, Search } from 'lucide-react';
import { Task, TaskColumn } from '../types/task';
import { TaskCard } from './TaskCard';
import { CreateTaskForm } from './CreateTaskForm';
import { SectionTitle } from './SectionTitle';
import { Button } from './ui/button';
import { Input } from './ui/input';
import { Badge } from './ui/badge';
import { tasks as initialTasks } from '../data/tasks';

const columns: TaskColumn[] = [
  { id: '1', title: 'To Do List', status: 'todo', color: 'bg-blue-50 border-blue-200' },
  { id: '2', title: 'In Progress', status: 'in-progress', color: 'bg-amber-50 border-amber-200' },
  { id: '3', title: 'In Review', status: 'in-review', color: 'bg-purple-50 border-purple-200' },
  { id: '4', title: 'Done', status: 'done', color: 'bg-green-50 border-green-200' },
];

export function TaskBoard() {
  const [tasks, setTasks] = useState<Task[]>(initialTasks);
  const [showCreateTask, setShowCreateTask] = useState(false);
  const [editingTask, setEditingTask] = useState<Task | null>(null);
  const [searchQuery, setSearchQuery] = useState('');

  const handleCreateTask = (taskData: Partial<Task>) => {
    if (editingTask) {
      // Update existing task
      setTasks(tasks.map(t => 
        t.id === editingTask.id 
          ? { ...t, ...taskData } 
          : t
      ));
      setEditingTask(null);
    } else {
      // Create new task
      const newTask: Task = {
        id: Date.now().toString(),
        title: taskData.title || '',
        description: taskData.description || '',
        status: taskData.status || 'todo',
        priority: taskData.priority || 'medium',
        category: taskData.category || 'Other',
        assignees: taskData.assignees || [],
        progress: taskData.progress || 0,
        comments: 0,
        attachments: 0,
        tags: taskData.tags || [],
        dueDate: taskData.dueDate,
      };
      setTasks([...tasks, newTask]);
    }
  };

  const handleDeleteTask = (taskId: string) => {
    setTasks(tasks.filter(t => t.id !== taskId));
  };

  const handleEditTask = (task: Task) => {
    setEditingTask(task);
    setShowCreateTask(true);
  };

  const handleMoveTask = (taskId: string, newStatus: Task['status']) => {
    setTasks(tasks.map(t => 
      t.id === taskId 
        ? { ...t, status: newStatus, progress: newStatus === 'done' ? 100 : t.progress } 
        : t
    ));
  };

  const filteredTasks = tasks.filter(task =>
    task.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
    task.description.toLowerCase().includes(searchQuery.toLowerCase()) ||
    task.category.toLowerCase().includes(searchQuery.toLowerCase())
  );

  const getTasksByStatus = (status: Task['status']) => {
    return filteredTasks.filter(task => task.status === status);
  };

  return (
    <motion.div
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      className="space-y-6"
    >
      {/* Header */}
      <div className="flex items-center justify-between flex-wrap gap-4">
        <SectionTitle title="Admin Tasks Management" icon={Filter} />
        <div className="flex items-center gap-3">
          <div className="relative">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
            <Input
              placeholder="Search tasks..."
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              className="pl-10 w-64 border-[#6B85A8]/30"
            />
          </div>
          <Button
            onClick={() => {
              setEditingTask(null);
              setShowCreateTask(true);
            }}
            className="bg-gradient-to-r from-[#243B53] to-[#4E5F7C] text-white hover:from-[#4E5F7C] hover:to-[#243B53]"
          >
            <Plus className="h-4 w-4 mr-2" />
            Create Task
          </Button>
        </div>
      </div>

      {/* Task Statistics */}
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        {columns.map((column, index) => {
          const columnTasks = getTasksByStatus(column.status);
          return (
            <motion.div
              key={column.id}
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: index * 0.1 }}
              className={`p-4 rounded-xl border-2 ${column.color}`}
            >
              <p className="text-sm text-gray-600 mb-1">{column.title}</p>
              <p className="text-2xl text-[#243B53]">{columnTasks.length}</p>
            </motion.div>
          );
        })}
      </div>

      {/* Kanban Board */}
      <div className="grid lg:grid-cols-4 gap-4">
        {columns.map((column, columnIndex) => {
          const columnTasks = getTasksByStatus(column.status);
          
          return (
            <motion.div
              key={column.id}
              initial={{ opacity: 0, x: -20 }}
              animate={{ opacity: 1, x: 0 }}
              transition={{ delay: columnIndex * 0.1 }}
              className="space-y-4"
            >
              {/* Column Header */}
              <div className={`p-4 rounded-xl border-2 ${column.color} sticky top-0 z-10`}>
                <div className="flex items-center justify-between">
                  <h3 className="text-[#243B53]">{column.title}</h3>
                  <Badge className="bg-[#243B53] text-white">
                    {columnTasks.length}
                  </Badge>
                </div>
              </div>

              {/* Tasks Container */}
              <div className="space-y-3 min-h-[400px]">
                <AnimatePresence>
                  {columnTasks.map((task, taskIndex) => (
                    <motion.div
                      key={task.id}
                      initial={{ opacity: 0, y: 20 }}
                      animate={{ opacity: 1, y: 0 }}
                      exit={{ opacity: 0, scale: 0.9 }}
                      transition={{ delay: taskIndex * 0.05 }}
                    >
                      <TaskCard
                        task={task}
                        onEdit={handleEditTask}
                        onDelete={handleDeleteTask}
                      />
                    </motion.div>
                  ))}
                </AnimatePresence>

                {columnTasks.length === 0 && (
                  <motion.div
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    className="p-8 text-center text-gray-400 border-2 border-dashed border-gray-200 rounded-xl"
                  >
                    <p className="text-sm">No tasks</p>
                  </motion.div>
                )}
              </div>
            </motion.div>
          );
        })}
      </div>

      {/* Create/Edit Task Form */}
      <CreateTaskForm
        open={showCreateTask}
        onClose={() => {
          setShowCreateTask(false);
          setEditingTask(null);
        }}
        onSubmit={handleCreateTask}
        editTask={editingTask}
      />
    </motion.div>
  );
}
