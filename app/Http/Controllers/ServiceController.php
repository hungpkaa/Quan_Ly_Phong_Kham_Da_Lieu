<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    // Hiển thị danh sách dịch vụ cho bệnh nhân
    public function index()
    {
        $services = Service::all(); 
        return view('services', compact('services')); 
    }

    // Hiển thị danh sách dịch vụ cho bệnh nhân view home
    public function index_home()
    {
        $services = Service::all(); 
        return view('home', compact('services')); 
    }

    // Hiển thị giao diện quản lý dịch vụ cho Admin
    public function manageServices()
    {
        $services = Service::all();
        return view('role.manageservices', compact('services'));
    }

    // Lưu dịch vụ mới vào database
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
    ]);

    if ($request->hasFile('image')) {
        // Tạo tên file duy nhất để tránh trùng lặp
        $imageName = time() . '_' . uniqid() . '.' . $request->image->getClientOriginalExtension();
        $request->image->move(public_path('uploads'), $imageName);
        $filePath = 'uploads/' . $imageName; // Lưu đường dẫn vào database
    } else {
        return redirect()->back()->with('error', 'Không thể tải file lên.');
    }

    Service::create([
        'name' => $request->name,
        'image' => $filePath, // Lưu đường dẫn file
    ]);

    return redirect()->route('admin.manageservices')->with('success', 'Dịch vụ được thêm thành công.');
}




    // Hiển thị form sửa dịch vụ
    public function edit($id)
    {
        Service::findOrFail($id);

        return redirect()->route('admin.manageservices', ['edit_id' => $id])
            ->with('success', 'Vui lòng chỉnh sửa dịch vụ trực tiếp trong danh sách.');
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
    
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);
    
        $imagePath = $service->image; // Giữ đường dẫn ảnh cũ nếu không có ảnh mới
    
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($service->image && file_exists(public_path($service->image))) {
                unlink(public_path($service->image)); // Xóa file cũ
            }
    
            // Tạo tên file duy nhất
            $imageName = time() . '_' . uniqid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads'), $imageName);
            $imagePath = 'uploads/' . $imageName; // Cập nhật đường dẫn mới
        }
    
        $service->update([
            'name' => $request->name,
            'image' => $imagePath, // Lưu đường dẫn mới hoặc giữ nguyên
        ]);
    
        return redirect()->route('admin.manageservices')->with('success', 'Dịch vụ được cập nhật thành công.');
    }
    

    // Xóa dịch vụ
    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        // Xóa ảnh khỏi storage
        if ($service->image && file_exists(public_path($service->image))) {
            unlink(public_path($service->image));
        }

        $service->delete();
        return redirect()->route('admin.manageservices')->with('success', 'Dịch vụ đã bị xóa.');
    }
}
