import { motion } from 'motion/react';
import { LucideIcon } from 'lucide-react';
import { Button } from './ui/button';

interface QuickActionCardProps {
  title: string;
  icon: LucideIcon;
  onClick: () => void;
  delay?: number;
}

export function QuickActionCard({ title, icon: Icon, onClick, delay = 0 }: QuickActionCardProps) {
  return (
    <motion.div
      initial={{ opacity: 0, scale: 0.9 }}
      animate={{ opacity: 1, scale: 1 }}
      transition={{ delay, duration: 0.4 }}
      whileHover={{ scale: 1.05, y: -8 }}
      whileTap={{ scale: 0.95 }}
    >
      <Button
        onClick={onClick}
        className="w-full h-24 bg-gradient-to-br from-[#243B53] to-[#4E5F7C] text-[#F4EDE4] hover:from-[#4E5F7C] hover:to-[#243B53] border-2 border-[#6B85A8]/40 shadow-lg hover:shadow-2xl relative overflow-hidden"
      >
        <motion.div
          className="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent"
          animate={{
            x: ['-100%', '200%'],
          }}
          transition={{
            duration: 3,
            repeat: Infinity,
            ease: "linear"
          }}
        />
        <div className="flex flex-col items-center gap-2 relative z-10">
          <motion.div
            whileHover={{ rotate: 360 }}
            transition={{ duration: 0.6 }}
          >
            <Icon className="h-8 w-8" />
          </motion.div>
          <span className="text-sm">{title}</span>
        </div>
      </Button>
    </motion.div>
  );
}