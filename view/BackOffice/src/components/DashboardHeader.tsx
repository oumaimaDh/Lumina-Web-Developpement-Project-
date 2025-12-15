import { motion } from 'motion/react';
import { Search, Bell, User } from 'lucide-react';
import { Input } from './ui/input';
import { Button } from './ui/button';
import { Avatar, AvatarFallback } from './ui/avatar';
import luminaLogo from 'figma:asset/97241871e1548f8ba14f8451e3398be28e1a9db3.png';

interface DashboardHeaderProps {
  userName?: string;
}

export function DashboardHeader({ userName = "Admin" }: DashboardHeaderProps) {
  const currentDate = new Date().toLocaleDateString('en-US', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    weekday: 'long'
  });

  return (
    <motion.div
      className="bg-white/95 backdrop-blur-sm rounded-2xl p-6 shadow-xl border-2 border-[#6B85A8]/40 mb-6"
      initial={{ y: -50, opacity: 0 }}
      animate={{ y: 0, opacity: 1 }}
      transition={{ duration: 0.5 }}
    >
      <div className="flex items-center justify-between gap-6">
        {/* Logo */}
        <motion.div 
          className="flex-shrink-0"
          initial={{ opacity: 0, scale: 0.8 }}
          animate={{ opacity: 1, scale: 1 }}
          transition={{ delay: 0.2 }}
        >
          <img 
            src={luminaLogo} 
            alt="Lumina Logo" 
            className="h-12 w-12 object-contain"
          />
        </motion.div>

        {/* Search */}
        <div className="flex-1 max-w-md relative">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-navy/40" />
          <Input
            placeholder="Search events, participants..."
            className="pl-10 bg-light-blue/10 border-light-blue/30 focus:border-navy"
          />
        </div>

        {/* Date */}
        <div className="hidden md:block text-right">
          <p className="text-sm text-navy/70">{currentDate}</p>
        </div>

        {/* Actions */}
        <div className="flex items-center gap-3">
          {/* Notifications */}
          <motion.div whileHover={{ scale: 1.1 }} whileTap={{ scale: 0.95 }}>
            <Button
              variant="ghost"
              size="icon"
              className="relative text-navy hover:bg-light-blue/20"
            >
              <Bell className="h-5 w-5" />
              <span className="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full" />
            </Button>
          </motion.div>

          {/* User Profile */}
          <motion.div
            className="flex items-center gap-3 pl-3 border-l border-light-blue/30"
            whileHover={{ scale: 1.05 }}
          >
            <div className="text-right hidden sm:block">
              <p className="text-sm text-navy">{userName}</p>
              <p className="text-xs text-navy/60">Administrator</p>
            </div>
            <Avatar className="h-10 w-10 border-2 border-light-blue/50">
              <AvatarFallback className="bg-navy text-cream">
                <User className="h-5 w-5" />
              </AvatarFallback>
            </Avatar>
          </motion.div>
        </div>
      </div>
    </motion.div>
  );
}