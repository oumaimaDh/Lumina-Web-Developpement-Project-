import { X, Send } from "lucide-react";
import { useState } from "react";

interface PostModalProps {
  isOpen: boolean;
  onClose: () => void;
  onSubmit: (content: string) => void;
}

export function PostModal({ isOpen, onClose, onSubmit }: PostModalProps) {
  const [postContent, setPostContent] = useState("");

  const handleSubmit = () => {
    if (postContent.trim()) {
      onSubmit(postContent);
      setPostContent("");
      onClose();
    }
  };

  if (!isOpen) return null;

  return (
    <div
      className="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
      onClick={(e) => {
        if (e.target === e.currentTarget) onClose();
      }}
    >
      <div className="bg-gradient-to-br from-[#e8d9de] to-[#f5f0ed] rounded-3xl max-w-2xl w-full p-8 relative shadow-2xl border-2 border-[#8195b8]/30">
        <button
          onClick={onClose}
          className="absolute top-6 right-6 text-[#1e3a5f] hover:bg-white/50 p-2 rounded-full transition-colors"
        >
          <X className="w-6 h-6" />
        </button>

        <h2 className="text-[#1e3a5f] mb-2">What's on Your Mind? 💭</h2>
        <p className="text-[#5b7ba4] text-sm mb-6">
          Share your community initiative ideas and connect with others making a difference!
        </p>

        <textarea
          value={postContent}
          onChange={(e) => setPostContent(e.target.value)}
          placeholder="Share your thoughts, ideas, or community updates... 💬"
          className="w-full h-40 p-4 rounded-2xl bg-white backdrop-blur-sm border-2 border-[#8195b8]/30 focus:border-[#5b7ba4] outline-none resize-none text-[#1e3a5f] placeholder:text-[#8195b8]"
          autoFocus
        />

        <div className="flex justify-between items-center mt-4">
          <p className="text-[#8195b8] text-sm">
            {postContent.length > 0 ? `${postContent.length} characters` : "Start typing..."}
          </p>
          <div className="flex gap-3">
            <button
              onClick={onClose}
              className="px-6 py-3 rounded-full text-[#5b7ba4] hover:bg-white/50 transition-colors"
            >
              Cancel
            </button>
            <button
              onClick={handleSubmit}
              disabled={!postContent.trim()}
              className="flex items-center gap-2 bg-gradient-to-r from-[#6b7da8] to-[#8195b8] text-white px-6 py-3 rounded-full hover:shadow-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span>Share Post!</span>
              <Send className="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}