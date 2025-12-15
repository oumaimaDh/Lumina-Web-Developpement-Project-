import { motion } from 'motion/react';
import { LucideIcon } from 'lucide-react';
import { Card } from './ui/card';

interface StatCardProps {
  title: string;
  value: string | number;
  icon: LucideIcon;
  trend?: string;
  color?: string;
  delay?: number;
}

export function StatCard({ 
  title, 
  value, 
  icon: Icon, 
  trend, 
  color = 'text-navy',
  delay = 0 
}: StatCardProps) {
  return (
    <motion.div
      initial={{ opacity: 0, scale: 0.9 }}
      animate={{ opacity: 1, scale: 1 }}
      transition={{ delay, duration: 0.5, type: "spring" }}
      whileHover={{ y: -8, scale: 1.02 }}
    >
      <Card className="p-6 bg-white/95 backdrop-blur-sm border-2 border-[#6B85A8]/40 hover:border-[#243B53]/60 transition-all shadow-lg hover:shadow-2xl">
        <div className="flex items-start justify-between">
          <div className="flex-1">
            <p className="text-[#4E5F7C] text-sm mb-1">{title}</p>
            <h3 className={`text-3xl ${color} mb-2`}>{value}</h3>
            {trend && (
              <p className="text-xs text-green-600">{trend}</p>
            )}
          </div>
          
          <motion.div
            className={`p-3 rounded-xl bg-gradient-to-br from-[#F4EDE4] to-[#E8D5D9] ${color}`}
            whileHover={{ rotate: 360, scale: 1.1 }}
            transition={{ duration: 0.6 }}
          >
            <Icon className="h-6 w-6" />
          </motion.div>
        </div>
      </Card>
    </motion.div>
  );
}