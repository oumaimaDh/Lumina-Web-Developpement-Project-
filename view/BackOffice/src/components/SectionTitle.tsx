import { motion } from 'motion/react';
import { LucideIcon } from 'lucide-react';

interface SectionTitleProps {
  title: string;
  icon?: LucideIcon;
  action?: React.ReactNode;
}

export function SectionTitle({ title, icon: Icon, action }: SectionTitleProps) {
  return (
    <motion.div
      className="flex items-center justify-between mb-4"
      initial={{ opacity: 0, x: -20 }}
      animate={{ opacity: 1, x: 0 }}
      transition={{ duration: 0.5 }}
    >
      <div className="flex items-center gap-3 bg-gradient-to-r from-[#243B53] to-[#4E5F7C] px-5 py-3 rounded-xl shadow-lg">
        {Icon && (
          <motion.div
            animate={{ rotate: [0, 360] }}
            transition={{ duration: 3, repeat: Infinity, ease: "linear" }}
          >
            <Icon className="h-5 w-5 text-white" />
          </motion.div>
        )}
        <h3 className="text-white">{title}</h3>
      </div>
      {action && <div>{action}</div>}
    </motion.div>
  );
}
