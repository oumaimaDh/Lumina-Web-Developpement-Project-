export interface Task {
  id: string;
  title: string;
  description: string;
  status: 'todo' | 'in-progress' | 'in-review' | 'done';
  priority: 'low' | 'medium' | 'high';
  category: string;
  assignees: string[];
  progress: number;
  comments: number;
  attachments: number;
  dueDate?: string;
  tags: string[];
}

export interface TaskColumn {
  id: string;
  title: string;
  status: Task['status'];
  color: string;
}
